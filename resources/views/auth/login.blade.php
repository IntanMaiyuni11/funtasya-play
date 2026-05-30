@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 bg-slate-50">
    <div class="max-w-5xl w-full bg-white rounded-[2.5rem] shadow-2xl shadow-blue-100 overflow-hidden flex flex-col md:flex-row">
        
       <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
    <div class="mb-8">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images3/logo.png') }}" class="h-10">
        </div>
        
        <div class="text-center">
            <h2 class="text-3xl font-black text-slate-900">Selamat Datang!</h2>
            <p class="text-slate-500 mt-2">Masuk ke akunmu untuk mulai belajar.</p>
        </div>
    </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl text-sm font-medium border border-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <input type="email" name="email" placeholder="Alamat Email" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Password" class="w-full px-6 py-4 bg-slate-50 border-0 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    <button type="button" onclick="togglePassword(event, 'password')" class="absolute right-6 top-4 text-slate-400 hover:text-slate-600">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </div>

                <div class="flex justify-between items-center text-sm px-1">
                    <label class="flex items-center text-slate-600"><input type="checkbox" name="remember" class="mr-2 rounded accent-blue-600"> Ingat saya</label>
                    <a href="{{ route('password.request') }}" class="text-blue-600 font-bold hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Masuk Sekarang
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-8">
                Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar sekarang</a>
            </p>
        </div>

        <div class="hidden md:flex w-1/2 bg-blue-600 items-center justify-center p-12">
            <div class="text-center text-white">
                <img src="{{ asset('images3/gambar1.png') }}" class="w-80 mx-auto mb-8 animate-bounce-slow">
                <h3 class="text-2xl font-black mb-2">Siap Belajar?</h3>
                <p class="text-blue-100">Ayo mulai petualangan seru bersama kami setiap hari!</p>
            </div>
        </div>
    </div>
</div>
@endsection

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
