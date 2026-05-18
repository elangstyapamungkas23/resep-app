<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f6f1eb]">

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

            <a href="/riwayat" class="text-orange-500 font-bold">
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

    <div class="px-16 py-16">

        <h1 class="text-6xl font-black mb-14">
            Riwayat Resep 👀
        </h1>

    <div class="grid grid-cols-3 gap-10">

        @foreach($riwayats as $item)

        <div class="bg-white p-8 rounded-[40px] shadow-lg">

            <h2 class="text-4xl font-black mb-4">
                {{ $item->resep->nama_resep }}
            </h2>

            <p class="text-slate-500 text-xl mb-8">
                Dilihat:
                {{ $item->created_at->diffForHumans() }}
            </p>

            <a
                href="/reseps/{{ $item->resep->id }}"
                class="bg-orange-500 text-white px-6 py-3 rounded-full"
            >
                Lihat Lagi
            </a>

        </div>

        @endforeach

    </div>

</div>

</body>
</html>