<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private array $allowedTypes = [
        'customer_driver',
        'customer_merchant',
        'merchant_driver',
    ];

    public function messages($orderId, $type)
{
    $this->validateChatType($type);

    $order = Order::with([
        'user',
        'driver.user',
        'restaurant',
    ])->findOrFail($orderId);

    $this->authorizeChatAccess($order, $type);

    if (request()->get('mark_read') == 1) {
        ChatMessage::where('order_id', $order->id)
            ->where('chat_type', $type)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);
    }

    $messages = ChatMessage::with('sender')
        ->where('order_id', $order->id)
        ->where('chat_type', $type)
        ->oldest()
        ->get()
        ->map(function ($chat) use ($order) {

            $sender = $chat->sender;

            return [
                'id' => $chat->id,
                'sender_id' => $chat->sender_id,
                'sender_name' => $sender->name ?? 'User',
                'sender_role' => $this->getRoleForUserInOrder($order, $chat->sender_id),

                'is_online' => $sender
                    && $sender->last_seen_at
                    && $sender->last_seen_at->gt(now()->subMinutes(2)),

                'last_seen_at' => $sender && $sender->last_seen_at
                    ? $sender->last_seen_at->diffForHumans()
                    : null,

                'message' => $chat->message,
                'image' => $chat->image ? asset('storage/'.$chat->image) : null,
                'is_me' => $chat->sender_id == Auth::id(),
                'time' => optional($chat->created_at)->format('H:i'),
            ];
        });

    return response()->json([
        'messages' => $messages,
    ]);
}

    public function send(Request $request, $orderId, $type)
    {
        $this->validateChatType($type);

        $request->validate([
    'message' => 'nullable|string|max:1000',
    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
]);

if (!$request->message && !$request->hasFile('image')) {
    return response()->json([
        'success' => false,
        'message' => 'Pesan atau foto wajib diisi.',
    ], 422);
}

        $order = Order::with([
            'user',
            'driver.user',
            'restaurant',
        ])->findOrFail($orderId);

        $this->authorizeChatAccess($order, $type);

        $message = trim($request->message);
        $senderRole = $this->getRoleForUserInOrder($order, Auth::id());

        $recentSameMessage = ChatMessage::where('order_id', $order->id)
            ->where('chat_type', $type)
            ->where('sender_id', Auth::id())
            ->where('message', $message)
            ->where('created_at', '>=', now()->subSeconds(3))
            ->latest()
            ->first();

        if ($recentSameMessage) {
            return response()->json([
                'success' => true,
                'duplicate_blocked' => true,
                'message' => [
                    'id' => $recentSameMessage->id,
                    'sender_id' => $recentSameMessage->sender_id,
                    'sender_name' => Auth::user()->name,
                    'sender_role' => $senderRole,
                    'message' => $recentSameMessage->message,
                    'is_me' => true,
                    'time' => optional($recentSameMessage->created_at)->format('H:i'),
                ],
            ]);
        }
$image = null;

if ($request->hasFile('image')) {
    $image = $request->file('image')->store('chat-images', 'public');
}
        $chat = ChatMessage::create([
    'order_id' => $order->id,
    'chat_type' => $type,
    'sender_id' => Auth::id(),
    'sender_role' => $senderRole,
    'message' => $message,
    'image' => $image,
    'is_read' => false,
]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chat->id,
                'sender_id' => $chat->sender_id,
                'sender_name' => Auth::user()->name,
                'sender_role' => $senderRole,
                'message' => $chat->message,
                'image' => $chat->image ? asset('storage/'.$chat->image) : null,
                'is_me' => true,
                'time' => optional($chat->created_at)->format('H:i'),
            ],
        ]);
    }

    public function unreadCount($orderId, $type)
    {
        $this->validateChatType($type);

        $order = Order::with([
            'user',
            'driver.user',
            'restaurant',
        ])->findOrFail($orderId);

        $this->authorizeChatAccess($order, $type);

        return response()->json([
            'count' => $this->getUnreadCount($order->id, $type),
        ]);
    }

    public function image($id)
{
    $chat = ChatMessage::with('order')->findOrFail($id);

    $this->authorizeChatAccess($chat->order, $chat->chat_type);

    if (!$chat->image) {
        abort(404);
    }

    $path = storage_path('app/public/'.$chat->image);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
}

    public function badge($orderId, $type)
    {
        $this->validateChatType($type);

        $order = Order::with([
            'user',
            'driver.user',
            'restaurant',
        ])->findOrFail($orderId);

        $this->authorizeChatAccess($order, $type);

        return response()->json([
            'count' => $this->getUnreadCount($order->id, $type),
        ]);
    }

    private function getUnreadCount($orderId, $type): int
    {
        return ChatMessage::where('order_id', $orderId)
            ->where('chat_type', $type)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    private function validateChatType($type): void
    {
        if (!in_array($type, $this->allowedTypes)) {
            abort(404);
        }
    }

    private function authorizeChatAccess(Order $order, $type): bool
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $isCustomer = $order->user_id == $user->id;
        $isDriver = $order->driver && $order->driver->user_id == $user->id;
        $isMerchant = $order->restaurant && $order->restaurant->owner_id == $user->id;
        $isAdmin = $user->role == 'admin';

        if ($isAdmin) {
            return true;
        }

        if ($type == 'customer_driver' && ($isCustomer || $isDriver)) {
            return true;
        }

        if ($type == 'customer_merchant' && ($isCustomer || $isMerchant)) {
            return true;
        }

        if ($type == 'merchant_driver' && ($isMerchant || $isDriver)) {
            return true;
        }

        abort(403);
    }

    private function getRoleForUserInOrder(Order $order, $userId): string
    {
        if ($order->user_id == $userId) {
            return 'customer';
        }

        if ($order->driver && $order->driver->user_id == $userId) {
            return 'driver';
        }

        if ($order->restaurant && $order->restaurant->owner_id == $userId) {
            return 'merchant';
        }

        $currentUser = Auth::user();

        if ($currentUser && $currentUser->id == $userId && $currentUser->role == 'admin') {
            return 'admin';
        }

        return 'user';
    }
}
