<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'order_id',
        'chat_type',
        'sender_id',
        'sender_role',
        'message',
        'image',
        'is_read',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}