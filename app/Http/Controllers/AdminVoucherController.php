<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVoucherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $query = Voucher::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->latest()->get();

        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('is_active', true)->count();
        $usedVouchers = Voucher::sum('used_count');
        $newUserVouchers = Voucher::where('is_new_user_only', true)->count();

        return view('admin.vouchers.index', compact(
            'vouchers',
            'search',
            'totalVouchers',
            'activeVouchers',
            'usedVouchers',
            'newUserVouchers'
        ));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type' => 'required|in:fixed,percent,free_delivery',
            'value' => 'nullable|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vouchers', 'public');
        }

        Voucher::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'image' => $imagePath,
            'type' => $request->type,
            'value' => $request->value ?? 0,
            'minimum_order' => $request->minimum_order ?? 0,
            'maximum_discount' => $request->maximum_discount,
            'quota' => $request->quota ?? 0,
            'used_count' => 0,
            'is_new_user_only' => $request->has('is_new_user_only'),
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect('/admin/vouchers')
            ->with('success', 'Voucher berhasil dibuat');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type' => 'required|in:fixed,percent,free_delivery',
            'value' => 'nullable|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $imagePath = $voucher->image;

        if ($request->hasFile('image')) {
            if ($voucher->image) {
                Storage::disk('public')->delete($voucher->image);
            }

            $imagePath = $request->file('image')->store('vouchers', 'public');
        }

        $voucher->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'image' => $imagePath,
            'type' => $request->type,
            'value' => $request->value ?? 0,
            'minimum_order' => $request->minimum_order ?? 0,
            'maximum_discount' => $request->maximum_discount,
            'quota' => $request->quota ?? 0,
            'is_new_user_only' => $request->has('is_new_user_only'),
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect('/admin/vouchers')
            ->with('success', 'Voucher berhasil diperbarui');
    }

    public function toggle($id)
    {
        $voucher = Voucher::findOrFail($id);

        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        return back()->with('success', 'Status voucher berhasil diperbarui');
    }

    public function delete($id)
    {
        $voucher = Voucher::findOrFail($id);

        if ($voucher->image) {
            Storage::disk('public')->delete($voucher->image);
        }

        $voucher->delete();

        return back()->with('success', 'Voucher berhasil dihapus');
    }
}