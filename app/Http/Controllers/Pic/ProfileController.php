<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('pic.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Name is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $data = ['name' => $request->name];

        if ($request->filled('password')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->withErrors(['password_lama' => 'Incorrect old password.'])->withInput();
            }
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('pic.profile.edit')
            ->with('success', 'Profile has been successfully updated.');
    }
}
