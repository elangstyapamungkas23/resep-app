<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe App</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#FFF7F1] overflow-x-hidden">

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

        <a
            href="/profile"
            class="text-orange-500 text-sm"
        >
            Lihat Profil
        </a>

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
    <section class="w-full min-h-screen flex items-center px-16">

        <!-- LEFT -->
        <div class="w-1/2">

            <p class="text-orange-500 font-semibold mb-4">
                Aplikasi Resep Modern
            </p>

            <h1 class="text-7xl font-black leading-tight text-gray-900">
                Temukan <br>
                Resep Favorit <br>
                Setiap Hari 🍲
            </h1>

            <p class="mt-6 text-gray-500 text-lg leading-relaxed w-[80%]">
                Jelajahi ribuan resep makanan modern dan tradisional.
                Simpan resep favoritmu dan akses lewat web maupun Flutter mobile app.
            </p>

            <div class="flex gap-5 mt-10">

                <!-- FIX BUTTON -->
                <a href="/reseps"
                   class="bg-orange-500 text-white px-8 py-4 rounded-full text-lg shadow-xl hover:scale-105 transition inline-block">

                    Jelajahi Resep

                </a>

                <button class="border-2 border-orange-500 text-orange-500 px-8 py-4 rounded-full text-lg hover:bg-orange-500 hover:text-white transition">
                    Download App
                </button>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="w-1/2 relative flex justify-center">

            <!-- BACKGROUND BLOB -->
            <div class="absolute w-[500px] h-[500px] bg-orange-200 rounded-full blur-3xl opacity-60"></div>

            <!-- IMAGE -->
            <img
                src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200"
                class="relative z-10 w-[550px] rounded-[40px] shadow-2xl rotate-3 hover:rotate-0 transition duration-500"
            >

            <!-- FLOATING CARD -->
            <div class="absolute bottom-10 left-0 bg-white p-5 rounded-3xl shadow-xl z-20">

                <p class="font-bold text-lg">
                    🔥 Trending Recipe
                </p>

                <p class="text-gray-500">
                    Mie Ayam Special
                </p>

            </div>

        </div>

    </section>

    <!-- CATEGORY -->
    <section class="px-16 py-24">

        <div class="flex justify-between items-center mb-12">

            <div>

                <p class="text-orange-500 font-semibold">
                    Kategori
                </p>

                <h2 class="text-5xl font-bold text-gray-900">
                    Cari Sesuai Selera
                </h2>

            </div>

        </div>

        <div class="grid grid-cols-4 gap-8">

    <!-- MAKANAN -->
    <a
        href="/reseps?kategori=1"
        class="bg-[#FFE7D6] p-8 rounded-[35px] hover:-translate-y-2 transition shadow-lg block"
    >

        <div class="text-6xl mb-5">
            🍜
        </div>

        <h3 class="text-2xl font-bold mb-2">
            Makanan
        </h3>

        <p class="text-gray-500">
            Resep makanan lezat sehari-hari
        </p>

    </a>

          <!-- DESSERT -->
<a
    href="/reseps?kategori=3"
    class="bg-[#FFD8D8] p-8 rounded-[35px] hover:-translate-y-2 transition shadow-lg block"
>

    <div class="text-6xl mb-5">
        🍰
    </div>

    <h3 class="text-2xl font-bold mb-2">
        Dessert
    </h3>

    <p class="text-gray-500">
        Aneka dessert dan makanan manis
    </p>

</a>

<!-- HEALTHY -->
<a
    href="/reseps?kategori=4"
    class="bg-[#D8F3DC] p-8 rounded-[35px] hover:-translate-y-2 transition shadow-lg block"
>

    <div class="text-6xl mb-5">
        🥗
    </div>

    <h3 class="text-2xl font-bold mb-2">
        Healthy
    </h3>

    <p class="text-gray-500">
        Menu sehat dan rendah kalori
    </p>

</a>

<!-- MINUMAN -->
<a
    href="/reseps?kategori=2"
    class="bg-[#DDEBFF] p-8 rounded-[35px] hover:-translate-y-2 transition shadow-lg block"
>

    <div class="text-6xl mb-5">
        ☕
    </div>

    <h3 class="text-2xl font-bold mb-2">
        Minuman
    </h3>

    <p class="text-gray-500">
        Minuman segar dan kekinian
    </p>

</a>

</div>

</section>

    <!-- RESEP POPULER -->
    <section class="px-16 py-24 bg-white">

        <div class="mb-14">

            <p class="text-5xl font-bold text-orange-500">
                Resep Populer
            </p>

        </div>

        <div class="grid grid-cols-3 gap-10">

            <!-- CARD -->
@foreach($trending as $resep)

<div class="bg-[#FFF7F1] rounded-[40px] overflow-hidden shadow-lg">

    @if($resep->gambar)
    <img
        src="{{ asset('storage/'.$resep->gambar) }}"
        class="w-full h-[330px] object-cover"
    >
    @endif

    <div class="p-8">

        <div class="flex justify-between items-center">

            <span class="text-orange-500 font-semibold">
                Resep Populer
            </span>

            <span class="text-yellow-500 font-bold">
                ⭐ {{ number_format($resep->rata_rating,1) }}
            </span>

        </div>

        <h2 class="text-4xl font-black mt-4">
            {{ $resep->nama_resep }}
        </h2>

        <p class="text-slate-500 mt-4">
            {{ Str::limit($resep->deskripsi, 80) }}
        </p>

        <p class="mt-3 text-sm text-slate-400">
            {{ $resep->total_rating }} Rating
        </p>

        <a
            href="/reseps/{{ $resep->id }}"
            class="inline-block mt-6 bg-orange-500 text-white px-6 py-3 rounded-full"
        >
            Lihat Resep
        </a>

    </div>

</div>

@endforeach

            </div>

        </div>

    </section>

</body>
</html>