<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountSettingsRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        return view('Backend.pages.account.index', compact('user'));
    }

    public function update(UpdateAccountSettingsRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // حدّث الاسم الكامل "name" من first + last
        $data['name'] = trim($data['first_name'] . ' ' . ($data['last_name'] ?? ''));

        // لو الباسورد فاضي ما نغيروش
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // لو فيه صورة جديدة
        if ($request->hasFile('avatar')) {
            // امسح القديمة لو موجودة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route('admin.account.edit')
            ->with('success', 'Account settings updated successfully.');
    }
}
