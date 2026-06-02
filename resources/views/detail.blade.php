@php
use Illuminate\Support\Str;

$averageRating = $resep->ratings->avg('rating');
$totalRating = $resep->ratings->count();

$userRating = auth()->check()
    ? $resep->ratings->where('user_id', auth()->id())->first()
    : null;

$isFavorit = auth()->check()
    ? $resep->favorits->where('user_id', auth()->id())->first()
    : null;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resep->nama_resep }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f6f1eb]">

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-10 py-6">

        <a href="/" class="text-3xl font-bold text-orange-500">
            🍜 Recipe App
        </a>

        <a href="/reseps"
           class="bg-orange-500 text-white px-6 py-3 rounded-full">
            Kembali
        </a>

    </nav>

    <!-- Detail -->
   <section class="max-w-7xl mx-auto px-6 py-12">

    <div class="grid lg:grid-cols-2 gap-12 items-start">

        <!-- GAMBAR -->
        <div>
            @if($resep->gambar)
                <img
                    src="{{ asset('storage/' . $resep->gambar) }}"
                    class="w-full h-[500px] object-cover rounded-3xl shadow-xl"
                >
            @else
                <img
                    src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200"
                    class="w-full h-[500px] object-cover rounded-3xl shadow-xl"
                >
            @endif
        </div>

        <!-- DETAIL -->
        <div>

            <p class="text-orange-500 font-semibold text-lg">
                Resep Pilihan
            </p>

            <h1 class="text-6xl font-black text-slate-900 mt-2 mb-5">
                {{ $resep->nama_resep }}
            </h1>

            <p class="text-xl text-slate-600 leading-relaxed mb-8">
                {{ $resep->deskripsi }}
            </p>

            <div class="flex gap-8 mb-8">

                <div>
                    <p class="font-bold text-slate-800">
                        👨‍🍳 Pembuat
                    </p>

                    <p class="text-slate-600">
                        {{ $resep->user->name ?? 'Admin' }}
                    </p>
                </div>

                <div>
                    <p class="font-bold text-slate-800">
                        📂 Kategori
                    </p>

                    <p class="text-slate-600">
                        {{ $resep->kategori->nama_kategori ?? '-' }}
                    </p>
                </div>

            </div>

                <!-- FORM RATING -->
                @auth

                <form action="/ratings" method="POST" class="mb-8">

                    @csrf

                    <input
                        type="hidden"
                        name="resep_id"
                        value="{{ $resep->id }}"
                    >

                    <div class="flex items-center gap-3 mb-5">

                        @for($i = 1; $i <= 5; $i++)

    <button
        type="submit"
        name="rating"
        value="{{ $i }}"
        class="text-5xl hover:scale-125 transition"
    >

        @if($userRating && $userRating->rating >= $i)

            <span class="text-yellow-400">
                ★
            </span>

        @else

            <span class="text-gray-300">
                ★
            </span>

        @endif

    </button>

@endfor

                    </div>

                    <p class="text-slate-500 text-lg">
                        Klik bintang untuk memberi rating
                    </p>

                </form>

                @else

                <a
                    href="/login"
                    class="bg-orange-500 text-white px-6 py-3 rounded-full"
                >
                    Login untuk memberi rating
                </a>

                @endauth

                <!-- HAPUS RATING -->
                @auth

                @if($userRating)

                <form
                    action="/ratings/{{ $userRating->id }}"
                    method="POST"
                    class="mb-10"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Hapus rating?')"
                        class="text-red-500 font-semibold hover:underline"
                    >
                        Hapus Rating
                    </button>

                </form>

                @endif

                @endauth

                <!-- FAVORIT BUTTON -->
                @php
                $isFavorit = auth()->check()
                ? $resep->favorits->where('user_id', auth()->id())->first()
                : null;
                @endphp

                @auth

                <form action="/favorit" method="POST">

                    @csrf

                    <input
                        type="hidden"
                        name="resep_id"
                        value="{{ $resep->id }}"
                    >

                    <button
                        type="submit"
                        class="
                            px-8 py-4 rounded-full text-xl text-white transition
                            {{ $isFavorit ? 'bg-red-500' : 'bg-orange-500' }}
                        "
                    >

                        @if($isFavorit)

                            ❤️ Hapus Favorit

                        @else

                            🤍 Simpan Favorit

                        @endif

                    </button>

                </form>

                @else

                <a
                    href="/login"
                    class="bg-orange-500 text-white px-6 py-3 rounded-full"
                >
                    Login untuk favorit
                </a>

                @endauth

            </div>

        </div>

    </section>

    <!-- BAHAN -->
    <div class="mt-16">

        <h2 class="text-4xl font-bold mb-6">
            🥬 Bahan-bahan
        </h2>

        <div class="bg-white rounded-3xl shadow-lg p-8">

            <div class="whitespace-pre-line text-slate-700 leading-8">
                {{ $resep->bahan }}
            </div>

        </div>

    </div>

    <!-- LANGKAH -->
    <div class="mt-12">

        <h2 class="text-4xl font-bold mb-6">
            👨‍🍳 Langkah Memasak
        </h2>

        <div class="bg-white rounded-3xl shadow-lg p-8">

            <div class="whitespace-pre-line text-slate-700 leading-8">
                {{ $resep->langkah }}
            </div>

        </div>

    </div>

</section>

    <!-- KOMENTAR -->
    <section class="px-16 pb-20">

        @auth

        <form action="/komentars" method="POST" class="mt-14 mb-10">

            @csrf

            <input
                type="hidden"
                name="resep_id"
                value="{{ $resep->id }}"
            >

            <h2 class="text-5xl font-black text-slate-900 mb-6">
                Komentar 💬
            </h2>

            <textarea
                name="isi"
                rows="5"
                placeholder="Tulis komentar..."
                class="w-full rounded-[30px] p-8 text-2xl border outline-none"
                required
            ></textarea>

            <button
                type="submit"
                class="mt-6 bg-orange-500 text-white px-8 py-4 rounded-full text-2xl"
            >
                Kirim Komentar
            </button>

        </form>

        @else

        <a
            href="/login"
            class="bg-orange-500 text-white px-6 py-3 rounded-full"
        >
            Login untuk komentar
        </a>

        @endauth

        <!-- LIST KOMENTAR -->
        <div class="space-y-6">

        @forelse($resep->komentars as $komentar)

        <div class="bg-white p-6 rounded-3xl shadow mb-5">

            <div class="flex justify-between items-start">

                <div>

                    <h3 class="font-bold text-xl text-slate-800">
                        {{ $komentar->user->name ?? 'User' }}
                    </h3>

                    <p class="text-slate-500 mt-2 text-lg">
                        {{ $komentar->komentar }}
                    </p>

                </div>

                @auth

                @if(auth()->id() == $komentar->user_id)

                <!-- BUTTON AREA -->
                <div class="flex gap-3">

                    <!-- BUTTON EDIT -->
                    <button
                        onclick="document.getElementById('edit-{{ $komentar->id }}').classList.toggle('hidden')"
                        class="bg-yellow-400 text-white px-4 py-2 rounded-xl"
                    >
                        Edit
                    </button>

                    <!-- HAPUS -->
                    <form
                        action="/komentars/{{ $komentar->id }}"
                        method="POST"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            onclick="return confirm('Hapus komentar?')"
                            class="bg-red-500 text-white px-4 py-2 rounded-xl"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

                @endif

                @endauth

            </div>

            <!-- FORM EDIT -->
            @auth

            @if(auth()->id() == $komentar->user_id)

            <form
                id="edit-{{ $komentar->id }}"
                action="/komentars/{{ $komentar->id }}"
                method="POST"
                class="hidden mt-5"
            >

                @csrf
                @method('PUT')

                <textarea
                    name="komentar"
                    class="w-full border rounded-2xl p-4"
                    rows="3"
                >{{ $komentar->komentar }}</textarea>

                <button
                    type="submit"
                    class="mt-3 bg-orange-500 text-white px-5 py-2 rounded-xl"
                >
                    Simpan Edit
                </button>

            </form>

            @endif

            @endauth

        </div>

        @empty

        <p class="text-slate-400 text-xl">
            Belum ada komentar 😢
        </p>

        @endforelse

        </div>

    </section>

</body>
</html>