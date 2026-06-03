<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f6f1eb] min-h-screen">

    <!-- NAVBAR -->
    <nav class="w-full flex items-center justify-between px-10 py-6 bg-[#FFF7F1]">

        <div class="text-3xl font-bold text-orange-500">
            🍜 Recipe App
        </div>

        <div class="flex gap-10 text-gray-700 font-medium">
            <a href="/">Home</a>
            <a href="/reseps">Resep</a>
            <a href="/about">About</a>
            <a href="/favorits">Favorit</a>
            <a href="/riwayat">Riwayat</a>
        </div>

        <a
            href="/reseps"
            class="bg-orange-500 text-white px-6 py-2 rounded-full"
        >
            Kembali
        </a>

    </nav>

    <!-- CONTAINER -->
    <div class="max-w-6xl mx-auto py-10">

        <!-- COVER -->
        <div class="h-64 rounded-t-[40px] overflow-hidden">

    @if(auth()->user()->cover)

        <img
            src="{{ asset('storage/' . auth()->user()->cover) }}"
            class="w-full h-full object-cover"
        >

    @else

        <img
            src="https://images.unsplash.com/photo-1498837167922-ddd27525d352"
            class="w-full h-full object-cover"
        >

    @endif

</div>

        <!-- CARD PROFILE -->
        <div class="bg-white rounded-b-[40px] shadow-lg px-10 pb-10">

            <div class="flex items-start gap-10">

                <!-- FOTO -->
                <div class="-mt-20">

                    @if(auth()->user()->foto)

                        <img
                            src="{{ asset('storage/' . auth()->user()->foto) }}"
                            class="w-40 h-40 rounded-full border-8 border-white object-cover"
                        >

                    @else

                        <img
                            src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&size=200"
                            class="w-40 h-40 rounded-full border-8 border-white"
                        >

                    @endif

                </div>

                <!-- INFO -->
                <div class="flex-1 pt-6">

                    <div class="flex justify-between items-start">

                        <div>

                            <h1 class="text-5xl font-black text-slate-900">
                                {{ auth()->user()->name }}
                            </h1>

                            <p class="text-slate-500 text-xl mt-2">
                                {{ auth()->user()->email }}
                            </p>

                            <p class="mt-5 text-lg text-slate-700">
                                {{ auth()->user()->bio ?? 'Belum ada bio.' }}
                            </p>

                        </div>

                        <a
                            href="/profile/edit"
                            class="
                                bg-orange-500
                                hover:bg-orange-600
                                text-white
                                px-8
                                py-4
                                rounded-full
                                text-lg
                                font-semibold
                            "
                        >
                            ✏️ Edit Profil
                        </a>

                    </div>

                    <!-- STATISTIK -->
                    <div class="flex gap-12 mt-8">

                        <div>
                            <h2 class="text-3xl font-black">
                                {{ $totalResep ?? 0 }}
                            </h2>
                            <p class="text-slate-500">
                                Resep
                            </p>
                        </div>

                        <div>
                            <h2 class="text-3xl font-black">
                                {{ $totalFavorit ?? 0 }}
                            </h2>
                            <p class="text-slate-500">
                                Favorit
                            </p>
                        </div>

                        <div>
                            <h2 class="text-3xl font-black">
                                {{ $totalRiwayat ?? 0 }}
                            </h2>
                            <p class="text-slate-500">
                                Riwayat
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- RESEP SAYA -->
<!-- RESEP SAYA -->

<div class="mt-16">

```
<h2 class="text-5xl font-black mb-8">
    Resep Saya 🍜
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

    @forelse($reseps as $resep)

    <div class="bg-white rounded-[30px] overflow-hidden shadow-lg">

        <!-- FOTO -->
        <div class="h-64 overflow-hidden">

            @if($resep->gambar)

                <img
                    src="{{ asset('storage/' . $resep->gambar) }}"
                    class="w-full h-full object-cover"
                >

            @else

                <img
                    src="https://images.unsplash.com/photo-1547592180-85f173990554"
                    class="w-full h-full object-cover"
                >

            @endif

        </div>

        <!-- CONTENT -->
        <div class="p-6">

            <h3 class="text-2xl font-bold text-slate-900 mb-3">
                {{ $resep->nama_resep }}
            </h3>

            <p class="text-slate-500 mb-6">
                {{ \Illuminate\Support\Str::limit($resep->deskripsi, 80) }}
            </p>

            <div class="flex gap-2 flex-wrap">

                <a
                    href="/reseps/{{ $resep->id }}"
                    class="
                        flex-1
                        text-center
                        bg-orange-500
                        hover:bg-orange-600
                        text-white
                        px-4
                        py-3
                        rounded-xl
                        font-semibold
                    "
                >
                    Detail
                </a>

                <a
                    href="/reseps/{{ $resep->id }}/edit"
                    class="
                        flex-1
                        text-center
                        bg-yellow-400
                        hover:bg-yellow-500
                        text-white
                        px-4
                        py-3
                        rounded-xl
                        font-semibold
                    "
                >
                    Edit
                </a>

                <form
                    action="/reseps/{{ $resep->id }}"
                    method="POST"
                    class="flex-1"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Hapus resep ini?')"
                        class="
                            w-full
                            bg-red-500
                            hover:bg-red-600
                            text-white
                            px-4
                            py-3
                            rounded-xl
                            font-semibold
                        "
                    >
                        Hapus
                    </button>

                </form>

            </div>

        </div>

    </div>

    @empty

    <div class="col-span-3 text-center py-10">

        <h3 class="text-3xl font-bold text-slate-700">
            Belum ada resep 🍜
        </h3>

    </div>

    @endforelse

</div>


</div>


        </div>

    </div>

</body>
</html>