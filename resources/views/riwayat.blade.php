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

            <a href="/trending" class="hover:text-orange-500 transition">
                Trending
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

    <div class="px-16 py-16">

        <div class="flex justify-between items-center mb-14">

            <h1 class="text-6xl font-black">
                Riwayat Resep 👀
            </h1>

            @if($riwayats->count())

            <form
                action="/riwayat/hapus-semua"
                method="POST"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    onclick="return confirm('Hapus semua riwayat?')"
                    class="bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 transition"
                >
                    🗑 Hapus Semua
                </button>

            </form>

            @endif

        </div>

        <div class="grid grid-cols-3 gap-10">

            @forelse($riwayats as $item)

            <div class="bg-white p-8 rounded-[40px] shadow-lg">

                <h2 class="text-4xl font-black mb-4">
                    {{ $item->resep->nama_resep }}
                </h2>

                <p class="text-slate-500 text-xl mb-8">
                    Dilihat:
                    {{ $item->created_at->diffForHumans() }}
                </p>

                <div class="flex gap-3">

                    <a
                        href="/reseps/{{ $item->resep->id }}"
                        class="bg-orange-500 text-white px-6 py-3 rounded-full"
                    >
                        Lihat Lagi
                    </a>

                    <form
                        action="/riwayat/{{ $item->id }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            onclick="return confirm('Hapus riwayat ini?')"
                            class="bg-red-500 text-white px-6 py-3 rounded-full"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

            </div>

            @empty

            <div class="col-span-3 text-center py-20">

                <h2 class="text-5xl font-black text-slate-800 mb-4">
                    Belum Ada Riwayat 👀
                </h2>

                <p class="text-2xl text-slate-500">
                    Belum ada resep yang pernah kamu lihat.
                </p>

            </div>

            @endforelse

        </div>

    </div>

</body>
</html>