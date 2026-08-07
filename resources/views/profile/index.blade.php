@extends('layouts.app')

@section('title', 'Profil - Budget Tracker')

@section('content')
<div class="max-w-2xl mx-auto space-y-4 md:space-y-6">
    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
        <div class="flex flex-col items-center text-center mb-6">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="Foto profil" class="w-20 h-20 rounded-full object-cover shadow-md">
            @else
                <div class="w-20 h-20 rounded-full bg-[#1BA37A] text-white flex items-center justify-center text-2xl font-bold shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <p class="mt-3 font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
            @if(auth()->user()->google_id)
                <span class="mt-2 text-xs px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-medium">
                    Terhubung dengan Google
                </span>
            @endif
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update', [], false) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required maxlength="255" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
            </div>
            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">URL Foto Profil</label>
                <input id="avatar" type="url" name="avatar" value="{{ old('avatar', auth()->user()->avatar ?? '') }}" placeholder="https://..." class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Opsional. Kosongkan untuk memakai huruf awal nama.</p>
            </div>
            <button type="submit" class="w-full bg-[#1BA37A] text-white py-3 rounded-xl text-sm font-semibold hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-sm">
                Simpan Profil
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white mb-1.5">Sinkronkan dari Google</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Ambil otomatis nama, email, dan foto profil dari akun Google kamu.</p>
            <a href="{{ route('google.sync', [], false) }}" onclick="showLoading()" class="flex items-center justify-center gap-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all btn-press shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15A11 11 0 002 7.06l3.84 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Sinkronkan dengan Google
            </a>
        </div>
    </div>
</div>
@endsection