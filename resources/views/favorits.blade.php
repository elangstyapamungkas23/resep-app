<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorit</title>

    <script src="https://cdn.tailwindcss.com"></script>
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

            <a href="/favorits" class="hover:text-orange-500 transition">
            Favorit
            </a>

            <a href="/riwayat" class="hover:text-orange-500 transition">
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

    <!-- TITLE -->
    <section class="px-16 pt-16 mb-14">

        <p class="text-orange-500 text-2xl font-semibold mb-4">
            Koleksi Favorit
        </p>

        <h1 class="text-7xl font-black text-slate-900 leading-tight mb-5">
            Resep Favorit ❤️
        </h1>

        <p class="text-2xl text-slate-500 leading-relaxed w-[60%]">
            Semua resep yang sudah kamu simpan ada di sini.
        </p>

    </section>

    <!-- LIST -->
    <section class="px-16 pb-20">

        <div class="grid grid-cols-3 gap-10">

            @forelse($favorits as $favorit)

            <div class="bg-white rounded-[40px] overflow-hidden shadow-lg hover:-translate-y-2 duration-300">

                <!-- IMAGE -->
                <div class="h-[320px] overflow-hidden">

                    @if($favorit->resep->gambar)

                        <img
                            src="{{ asset('storage/' . $favorit->resep->gambar) }}"
                            class="w-full h-full object-cover"
                        >

                    @else

                        <img
                            src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200"
                            class="w-full h-full object-cover"
                        >

                    @endif

                </div>

                <!-- CONTENT -->
                <div class="p-8">

                    <div class="flex justify-between items-center mb-4">

                        <p class="text-red-500 font-semibold text-lg">
                            ❤️ Favorit
                        </p>

                    </div>

                    <h3 class="text-5xl font-black text-slate-900 mb-4 leading-tight">
                        {{ $favorit->resep->nama_resep }}
                    </h3>

                    <p class="text-2xl text-slate-500 leading-relaxed mb-8">
                        {{ \Illuminate\Support\Str::limit($favorit->resep->deskripsi, 80) }}
                    </p>

                    <a
                        href="/reseps/{{ $favorit->resep->id }}"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-full text-xl font-semibold duration-300 inline-block"
                    >
                        Lihat Detail
                    </a>

                </div>

            </div>

            @empty

            <div class="col-span-3 text-center py-20">

                <h2 class="text-5xl font-black text-slate-800 mb-4">
                    Belum Ada Favorit ❤️
                </h2>

                <p class="text-2xl text-slate-500">
                    Simpan resep favorit terlebih dahulu.
                </p>

            </div>

            @endforelse

        </div>

    </section>

</body>
</html>