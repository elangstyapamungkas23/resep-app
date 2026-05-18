@php
use Illuminate\Support\Str;
@endphp

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

    <!-- TITLE -->
    <section class="px-16 pt-16 mb-14">

        <p class="text-orange-500 text-2xl font-semibold mb-4">
            Jelajahi Resep
        </p>

        <h1 class="text-7xl font-black text-slate-900 leading-tight mb-5">
            Temukan Menu Favoritmu <br>
            
        </h1>

        <p class="text-2xl text-slate-500 leading-relaxed w-[100%]">
            Pilihan resep terbaik dengan tampilan modern dan mudah dijelajahi.
        </p>

    </section>

    <!-- RECIPE LIST -->
    <section class="px-16 pb-20">
<!-- SEARCH -->
<form action="/reseps" method="GET" class="mb-14">

    <div class="flex items-center gap-5">

        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari resep favoritmu..."
            class="
                w-full
                bg-white
                px-8
                py-5
                rounded-full
                text-xl
                shadow-lg
                outline-none
            "
        >

        <button
            type="submit"
            class="
                bg-orange-500
                hover:bg-orange-600
                text-white
                px-10
                py-5
                rounded-full
                text-xl
                font-semibold
                duration-300
            "
        >
            Cari
        </button>

    </div>

</form>

<!-- KATEGORI -->
<div class="flex gap-4 mb-14 flex-wrap">

    <a
        href="/reseps"
        class="
            px-6 py-3 rounded-full text-lg font-semibold
            {{ !$kategori ? 'bg-orange-500 text-white' : 'bg-white text-slate-700' }}
        "
    >
        Semua
    </a>

    @foreach($kategoris as $item)

        <a
            href="/reseps?kategori={{ $item->id }}"
            class="
                px-6 py-3 rounded-full text-lg font-semibold transition
                {{ $kategori == $item->id
                    ? 'bg-orange-500 text-white'
                    : 'bg-white text-slate-700'
                }}
            "
        >
            {{ $item->nama_kategori }}
        </a>

    @endforeach

</div>

        <!-- GRID -->
        <div class="grid grid-cols-3 gap-10">

            @forelse($reseps as $resep)

            <div class="bg-white rounded-[40px] overflow-hidden shadow-lg hover:-translate-y-2 duration-300">

                <!-- IMAGE -->
                <div class="h-[320px] overflow-hidden">

                    @if($resep->gambar)

                        <img
                            src="{{ asset('storage/' . $resep->gambar) }}"
                            class="w-full h-full object-cover"
                            alt="{{ $resep->nama_resep }}"
                        >

                    @else

                        <img
                            src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200&auto=format&fit=crop"
                            class="w-full h-full object-cover"
                            alt="Recipe Image"
                        >

                    @endif

                </div>

                <!-- CONTENT -->
                <div class="p-8">

                    <div class="flex justify-between items-center mb-4">

                        <p class="text-orange-500 font-semibold text-lg">
                            Recipe
                        </p>

                        <p class="text-yellow-500 text-lg">
                            ⭐ 4.9
                        </p>

                    </div>

                    <h3 class="text-5xl font-black text-slate-900 mb-4 leading-tight">
                        {{ $resep->nama_resep }}
                    </h3>

                    <p class="text-2xl text-slate-500 leading-relaxed mb-8">
                        {{ Str::limit($resep->deskripsi, 80) }}
                    </p>

                    <!-- DETAIL BUTTON -->
                    <a
                        href="/reseps/{{ $resep->id }}"
                        class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-full text-xl font-semibold duration-300"
                    >
                        Lihat Detail
                    </a>

                </div>

            </div>

            @empty

            <div class="col-span-3 text-center py-20">

                <h2 class="text-5xl font-black text-slate-800 mb-4">
                    Belum Ada Resep 🍜
                </h2>

                <p class="text-2xl text-slate-500">
                    Tambahkan resep terlebih dahulu dari database.
                </p>

            </div>

            @endforelse

        </div>

    </section>

</body>
</html>