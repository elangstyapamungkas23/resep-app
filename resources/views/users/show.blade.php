
<!DOCTYPE html>
<html>
<head>
    <title>{{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f6f1eb] min-h-screen">

<div class="max-w-6xl mx-auto py-10">

    <!-- BUTTON KEMBALI -->
    <div class="mb-6">

        <a
            href="{{ url()->previous() }}"
            class="
                inline-flex
                items-center
                gap-2
                bg-orange-500
                hover:bg-orange-600
                text-white
                px-6
                py-3
                rounded-full
                font-semibold
            "
        >
            ← Kembali
        </a>

    </div>

    <!-- COVER -->
    <div class="h-80 rounded-t-[40px] overflow-hidden">

        @if($user->cover)

            <img
                src="{{ asset('storage/' . $user->cover) }}"
                class="w-full h-full object-cover"
            >

        @else

            <img
                src="https://images.unsplash.com/photo-1498837167922-ddd27525d352"
                class="w-full h-full object-cover"
            >

        @endif

    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white rounded-b-[40px] shadow-lg px-10 pb-10">

        <div class="flex items-start gap-10">

            <!-- FOTO -->
            <div class="-mt-20">

                @if($user->foto)

                    <img
                        src="{{ asset('storage/' . $user->foto) }}"
                        class="
                            w-40
                            h-40
                            rounded-full
                            border-8
                            border-white
                            object-cover
                        "
                    >

                @else

                    <img
                        src="https://ui-avatars.com/api/?name={{ $user->name }}&size=200"
                        class="
                            w-40
                            h-40
                            rounded-full
                            border-8
                            border-white
                        "
                    >

                @endif

            </div>

            <!-- INFO -->
            <div class="flex-1 pt-6">

                <h1 class="text-5xl font-black text-slate-900">
                    {{ $user->name }}
                </h1>

                <p class="text-slate-500 text-xl mt-2">
                    {{ $user->email }}
                </p>

                <p class="mt-5 text-lg text-slate-700">
                    {{ $user->bio ?? 'Belum ada bio.' }}
                </p>

                <div class="flex gap-12 mt-8">

                    <div>
                        <h2 class="text-3xl font-black">
                            {{ $reseps->count() }}
                        </h2>

                        <p class="text-slate-500">
                            Resep
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- RESEP USER -->
    <div class="mt-16">

        <h2 class="text-5xl font-black mb-8">
            Resep dari {{ $user->name }} 🍜
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            @forelse($reseps as $resep)

                <a
                    href="/reseps/{{ $resep->id }}"
                    class="
                        bg-white
                        rounded-3xl
                        shadow-lg
                        overflow-hidden
                        hover:-translate-y-1
                        transition
                    "
                >

                    @if($resep->gambar)

                        <img
                            src="{{ asset('storage/'.$resep->gambar) }}"
                            class="w-full h-56 object-cover"
                        >

                    @endif

                    <div class="p-5">

                        <h3 class="font-bold text-xl">
                            {{ $resep->nama_resep }}
                        </h3>

                    </div>

                </a>

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

</body>
</html>

