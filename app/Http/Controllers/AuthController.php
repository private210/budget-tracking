<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Akun berhasil dibuat, selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function redirectToGoogle(Request $request)
    {
        if (! config('services.google.client_id')) {
            return redirect(auth()->check() ? '/profile' : '/login')
                ->with('error', 'Login Google belum dikonfigurasi.');
        }

        $request->session()->put('google_intent', auth()->check() ? 'sync' : 'login');

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $google = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('google oauth failed: '.$e->getMessage());

            return redirect('/login')->with('error', 'Gagal masuk dengan Google, coba lagi.');
        }

        $intent = $request->session()->pull('google_intent', 'login');

        if ($intent === 'sync' && auth()->check()) {
            return $this->syncWithGoogle($google);
        }

        return $this->loginWithGoogle($google, $request);
    }

    private function loginWithGoogle($google, Request $request)
    {
        $user = User::where('google_id', $google->getId())
            ->orWhere('email', $google->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
            ]);
        } else {
            $user = User::create([
                'name' => $google->getName() ?: $google->getEmail(),
                'email' => $google->getEmail(),
                'google_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
                'password' => Str::password(32),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Berhasil masuk dengan Google!');
    }

    private function syncWithGoogle($google)
    {
        $user = auth()->user();

        if ($user->google_id === $google->getId()
            || ($user->google_id === null && $user->email === $google->getEmail())) {
            $user->update([
                'google_id' => $google->getId(),
                'name' => $google->getName() ?: $user->name,
                'email' => $google->getEmail(),
                'avatar' => $google->getAvatar(),
            ]);

            return redirect('/profile')->with('success', 'Data profil berhasil disinkronkan dari Google!');
        }

        return redirect('/profile')->with('error', 'Akun Google tidak cocok dengan profil ini.');
    }
}
