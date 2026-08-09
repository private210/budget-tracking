<?php

namespace App\Http\Controllers;

use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'current_password' => 'nullable|string',
            'password' => ['nullable', 'max:72', 'confirmed', new StrongPassword],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $user->avatar = 'data:'.$file->getMimeType().';base64,'.base64_encode($file->get());
        }

        if ($request->filled('password')) {
            if ($user->google_id || Hash::check($validated['current_password'], $user->password)) {
                $user->password = Hash::make($validated['password']);
            } else {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
            }
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
