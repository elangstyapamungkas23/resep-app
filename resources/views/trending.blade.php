<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending Recipe</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f6f1eb]">

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

            <a href="/trending" class="text-orange-500 font-bold">
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

    <div class="max-w-7xl mx-auto py-12 px-8">

        <h1 class="text-6xl font-black text-orange-500 mb-12">
           🔥 Top 30 Trending Recipes
        </h1>

        <div class="grid md:grid-cols-3 gap-8">

            @forelse($reseps as $index => $resep)

            <a
                href="/reseps/{{ $resep->id }}"
                class="bg-white rounded-[35px] overflow-hidden shadow-lg hover:scale-105 transition"
            >
                @if($resep->gambar)
                <img
                    src="{{ asset('storage/'.$resep->gambar) }}"
                    class="w-full h-72 object-cover"
                >
                @endif

                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-orange-500 font-bold">
                            @if($index == 0)
                                🥇 Trending #1
                            @elseif($index == 1)
                                🥈 Trending #2
                            @elseif($index == 2)
                                🥉 Trending #3
                            @else
                                🔥 Trending #{{ $index + 1 }}
                            @endif
                        </span>

                        <span class="font-bold text-yellow-500">
                            ⭐ {{ number_format($resep->ratings_avg_rating ?? 0, 1) }}
                        </span>
                    </div>

                    <h2 class="text-3xl font-black mb-2">
                        {{ $resep->nama_resep }}
                    </h2>

                    <p class="text-slate-500">
                        Oleh {{ $resep->user->name }}
                    </p>

                    <p class="mt-3 text-sm text-slate-500">
                        {{ $resep->ratings_count }} Rating
                    </p>
                </div>
            </a>

            @empty

            <div class="col-span-3 text-center py-20">
                <h2 class="text-4xl font-black">
                    Belum Ada Resep Trending 🔥
                </h2>
            </div>

            @endforelse

        </div>

    </div>

</body>
</html>