<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Recipe App</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f6f1eb] overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="w-full flex items-center justify-between px-10 py-6 bg-[#FFF7F1]">

        <div class="text-3xl font-bold text-orange-500">
            🍜 Recipe App
        </div>

        <div class="flex gap-10 text-gray-700 font-medium">

            <a href="/" class="hover:text-orange-500 transition">
                Home
            </a>

            <a href="/reseps" class="hover:text-orange-500 transition">
                Resep
            </a>

            <a href="/trending" class="hover:text-orange-500 transition">
                Trending
            </a>

            <a href="/about" class="hover:text-orange-500 transition">
                About
            </a>

            <a
            href="{{ auth()->check() ? '/favorits' : '/login' }}"
            class="hover:text-orange-500 transition"
            >
            Favorit
            </a>

            <a
            href="{{ auth()->check() ? '/riwayat' : '/login' }}"
            class="hover:text-orange-500 transition"
            >
            Riwayat
            </a>

        </div>

        @auth

<div class="flex items-center gap-4">

    <div class="flex items-center gap-3">

    @if(auth()->user()->foto)

        <img
            src="{{ asset('storage/' . auth()->user()->foto) }}"
            class="w-12 h-12 rounded-full object-cover"
        >

    @else

        <img
            src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}"
            class="w-12 h-12 rounded-full"
        >

    @endif

    <div>

    <p class="font-semibold text-slate-700">
        {{ auth()->user()->name }}
    </p>

    <div class="flex gap-2 text-sm">

        <a
            href="/profile"
            class="text-orange-500"
        >
            Lihat Profil
        </a>

        @if(auth()->user()->role == 'admin')

            <span class="text-slate-400">|</span>

            <a
                href="/admin"
                class="text-red-500 font-semibold"
            >
                Admin
            </a>

        @endif

    </div>

</div>

</div>

    <form action="/logout" method="POST">
        @csrf

        <button
            class="bg-red-500 text-white px-5 py-2 rounded-full hover:scale-105 transition"
        >
            Logout
        </button>

    </form>

</div>

@endauth


@guest

<a
    href="/login"
    class="bg-orange-500 text-white px-6 py-2 rounded-full shadow-lg hover:scale-105 transition"
>
    Login
</a>

@endguest

    </nav>

    <!-- HERO -->
    <section class="px-16 py-20">

        <p class="text-orange-500 text-2xl font-semibold mb-5">
            About Recipe App
        </p>

        <h1 class="text-6xl font-black text-slate-900 leading-tight mb-8">
            Temukan Resep Favorit <br>
            Dengan Pengalaman Modern 🍜
        </h1>

        <p class="text-2xl text-slate-500 leading-relaxed w-[70%]">
            Recipe App adalah aplikasi berbasis Laravel dan Flutter
            yang membantu pengguna menemukan, menyimpan,
            dan mengeksplorasi berbagai resep makanan
            dengan tampilan modern dan mudah digunakan.
        </p>

    </section>

    <!-- WHY -->
    <section class="px-16 pb-20">

        <div class="bg-white rounded-[40px] p-14 shadow-lg">

            <h2 class="text-5xl font-black text-slate-900 mb-8">
                Kenapa Aplikasi Ini Dibuat?
            </h2>

            <p class="text-2xl text-slate-500 leading-relaxed">
                Aplikasi ini dibuat karena banyak pengguna kesulitan
                mencari resep yang praktis, modern, dan mudah diakses.

                Dengan adanya fitur kategori, favorit, rating,
                komentar, dan riwayat, pengguna dapat menikmati
                pengalaman mencari resep yang lebih interaktif.
            </p>

        </div>

    </section>

    <!-- CREATOR -->
    <section class="px-16 pb-24">

        <div class="grid grid-cols-2 gap-14 items-center">

            <!-- FOTO -->
            <div>

                <img
                    src="{{ asset('images/elang.jpeg') }}"
                    class="rounded-[40px] shadow-2xl w-full"
                >

            </div>

            <!-- CONTENT -->
            <div>

                <p class="text-orange-500 text-2xl font-semibold mb-4">
                    Developed By
                </p>

                <h2 class="text-6xl font-black text-slate-900 mb-8 leading-tight">
                    Elang Stya Pamungkas
                </h2>

                <p class="text-2xl text-slate-500 leading-relaxed mb-10">
                    Mahasiswa STMIK Widya Utama yang mengembangkan
                    aplikasi Recipe App berbasis Laravel dan Flutter.

                    Fokus utama aplikasi ini adalah memberikan
                    pengalaman pengguna yang modern, nyaman,
                    dan mudah digunakan.
                </p>

                <!-- TECH -->
                <div class="flex flex-wrap gap-5">

                    <div class="bg-orange-500 text-white px-6 py-3 rounded-full text-xl">
                        Laravel
                    </div>

                    <div class="bg-sky-500 text-white px-6 py-3 rounded-full text-xl">
                        Flutter
                    </div>

                    <div class="bg-slate-800 text-white px-6 py-3 rounded-full text-xl">
                        TailwindCSS
                    </div>

                    <div class="bg-green-500 text-white px-6 py-3 rounded-full text-xl">
                        MySQL
                    </div>

                    <div class="bg-pink-500 text-white px-6 py-3 rounded-full text-xl">
                        REST API
                    </div>

                </div>

            </div>

        </div>

    </section>

</body>
</html>