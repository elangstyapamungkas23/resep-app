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

            <a href="/favorits" class="hover:text-orange-500 transition">
            Favorit
            </a>

            <a href="/riwayat" class="hover:text-orange-500 transition">
            Riwayat
            </a>

        </div>

        @auth

<div class="flex items-center gap-4">

    <p class="font-semibold text-slate-700">
        {{ auth()->user()->name }}
    </p>

    <form action="/logout" method="POST">
        @csrf

        <button
            class="bg-red-500 text-white px-5 py-2 rounded-full"
        >
            Logout
        </button>

    </form>

</div>

@else

<a
    href="/login"
    class="bg-orange-500 text-white px-6 py-2 rounded-full shadow-lg hover:scale-105 transition"
>
    Login
</a>

@endauth

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

            <button class="text-orange-500 font-semibold">
                Lihat Semua →
            </button>

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

            <p class="text-orange-500 font-semibold">
                Resep Populer
            </p>

            <h2 class="text-5xl font-bold text-gray-900">
                Trending Minggu Ini
            </h2>

        </div>

        <div class="grid grid-cols-3 gap-10">

            <!-- CARD -->
            <div class="bg-[#FFF7F1] rounded-[35px] overflow-hidden shadow-lg hover:-translate-y-2 transition">

                <img
                    src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=1200"
                    class="h-64 w-full object-cover"
                >

                <div class="p-7">

                    <div class="flex justify-between items-center mb-3">

                        <p class="text-orange-500 font-semibold">
                            Makanan
                        </p>

                        <p class="text-yellow-500">
                            ⭐ 4.9
                        </p>

                    </div>

                    <h3 class="text-3xl font-bold mb-3">
                        Mie Ayam
                    </h3>

                    <p class="text-gray-500">
                        Resep mie ayam rumahan yang gurih dan lezat.
                    </p>

                </div>

            </div>

            <!-- CARD -->
            <div class="bg-[#FFF7F1] rounded-[35px] overflow-hidden shadow-lg hover:-translate-y-2 transition">

                <img
                    src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1200"
                    class="h-64 w-full object-cover"
                >

                <div class="p-7">

                    <div class="flex justify-between items-center mb-3">

                        <p class="text-orange-500 font-semibold">
                            Fast Food
                        </p>

                        <p class="text-yellow-500">
                            ⭐ 4.8
                        </p>

                    </div>

                    <h3 class="text-3xl font-bold mb-3">
                        Pizza
                    </h3>

                    <p class="text-gray-500">
                        Pizza homemade dengan topping premium.
                    </p>

                </div>

            </div>

            <!-- CARD -->
            <div class="bg-[#FFF7F1] rounded-[35px] overflow-hidden shadow-lg hover:-translate-y-2 transition">

                <img
                    src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=1200"
                    class="h-64 w-full object-cover"
                >

                <div class="p-7">

                    <div class="flex justify-between items-center mb-3">

                        <p class="text-orange-500 font-semibold">
                            Healthy
                        </p>

                        <p class="text-yellow-500">
                            ⭐ 4.7
                        </p>

                    </div>

                    <h3 class="text-3xl font-bold mb-3">
                        Salad
                    </h3>

                    <p class="text-gray-500">
                        Salad sehat dengan sayuran fresh.
                    </p>

                </div>

            </div>

        </div>

    </section>

</body>
</html>