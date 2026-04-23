@extends('layouts.auth')

@section('content')
<div class="flex flex-col items-center px-6 py-8 md:py-12">
    <div class="w-full max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-center gap-12 lg:gap-24">
        
        <div class="w-full md:w-1/2 flex justify-center md:justify-end">
            <div class="w-full max-w-[420px]">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/logo_funtasyaplay.png') }}" class="h-10">
                </div>

                <h2 class="text-center text-[24px] font-bold mb-8 text-black tracking-tight">
                    Register dulu yuk!
                </h2>

                {{-- PESAN ERROR GLOBAL (Termasuk Email Duplikat) --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                        <p class="font-bold text-sm">Ups! Ada masalah:</p>
                        <ul class="text-xs mt-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- NAMA LENGKAP --}}
                    <div class="w-full">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus
                            class="w-full px-5 py-3.5 bg-white border rounded-xl focus:border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none text-[15px] placeholder:text-gray-400 transition-all shadow-sm {{ $errors->has('name') ? 'border-red-500' : 'border-gray-400' }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="w-full">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
                            class="w-full px-5 py-3.5 bg-white border rounded-xl focus:border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none text-[15px] placeholder:text-gray-400 transition-all shadow-sm {{ $errors->has('email') ? 'border-red-500' : 'border-gray-400' }}">
                        @error('email')
                            <p class="text-red-500 text-[11px] mt-1 ml-1 font-medium italic">Email ini sudah terdaftar!</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="relative w-full">
                        <input type="password" id="password" name="password" placeholder="Password" required
                            class="w-full px-5 py-3.5 bg-white border rounded-xl focus:border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none text-[15px] placeholder:text-gray-400 shadow-sm transition-all {{ $errors->has('password') ? 'border-red-500' : 'border-gray-400' }}">
                        
                        <button type="button" onclick="togglePassword(event, 'password')" 
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-regular fa-eye text-lg"></i>
                        </button>
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="relative w-full">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required
                            class="w-full px-5 py-3.5 bg-white border border-gray-400 rounded-xl focus:border-pink-500 focus:ring-1 focus:ring-pink-500 outline-none text-[15px] placeholder:text-gray-400 shadow-sm transition-all">

                         <button type="button" onclick="togglePassword(event, 'password_confirmation')" 
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fa-regular fa-eye text-lg"></i>
                        </button>
                    </div>

                    <button type="submit" class="w-full bg-[#E5488E] text-white py-4 rounded-2xl text-[16px] font-bold hover:bg-[#d13d7f] transition shadow-md mt-4">
                        Register
                    </button>

                    <div class="flex items-center py-6">
                        <div class="flex-grow border-t border-gray-400"></div>
                        <span class="px-4 text-[14px] text-gray-500 font-medium">Atau</span>
                        <div class="flex-grow border-t border-gray-400"></div>
                    </div>

                    <div class="flex justify-center gap-4">
                        <a href="{{ route('social.login', 'google') }}"
                            class="w-14 h-12 border border-pink-400 rounded-2xl flex items-center justify-center hover:bg-pink-50 transition shadow-sm bg-white">
                            <img src="https://cdn-icons-png.flaticon.com/512/300/300221.png" class="w-6 h-6">
                        </a>
                        <a href="{{ route('social.login', 'facebook') }}"
                            class="w-14 h-12 border border-pink-400 rounded-2xl flex items-center justify-center hover:bg-pink-50 transition shadow-sm bg-white">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" class="w-6 h-6">
                        </a>
                    </div>

                    <p class="text-center text-[14px] pt-6 text-black font-medium">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline ml-1">
                            Login dulu yuk!
                        </a>
                    </p>
                </form>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 justify-start items-center">
            <img src="{{ asset('images/Kucing.png') }}" 
                 class="w-[450px] lg:w-[550px] object-contain drop-shadow-lg">
        </div>
    </div>
</div>

<script>
function togglePassword(e, id) {
    const input = document.getElementById(id);
    const icon = e.currentTarget.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection