<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f6f1eb]">

<div class="max-w-3xl mx-auto mt-20 bg-white p-10 rounded-[40px] shadow-lg">

    <h1 class="text-5xl font-black mb-10">
        Edit Profil
    </h1>

    <form action="/profile/edit" method="POST" enctype="multipart/form-data">

        @csrf

        <!-- Nama -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold">
                Nama
            </label>

            <input
                type="text"
                name="name"
                value="{{ auth()->user()->name }}"
                class="w-full border p-4 rounded-xl"
            >
        </div>

        <!-- Bio -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold">
                Bio
            </label>

            <textarea
                name="bio"
                rows="4"
                class="w-full border p-4 rounded-xl"
            >{{ auth()->user()->bio }}</textarea>
        </div>

        <!-- Foto Profil -->
        <div class="mb-6">
            <label class="block mb-2 font-semibold">
                Foto Profil
            </label>

            <input
                type="file"
                name="foto"
                class="w-full border p-4 rounded-xl"
            >
        </div>

        <!-- Foto Cover -->
        <div class="mb-8">
            <label class="block mb-2 font-semibold">
                Foto Cover
            </label>

            <input
                type="file"
                name="cover"
                class="w-full border p-4 rounded-xl"
            >
        </div>

        <!-- Tombol -->
        <div class="flex gap-4">

            <button
                type="submit"
                class="
                    bg-orange-500
                    hover:bg-orange-600
                    text-white
                    px-8
                    py-4
                    rounded-full
                    font-semibold
                "
            >
                Simpan Profil
            </button>

            <a
                href="/profile"
                class="
                    bg-slate-300
                    hover:bg-slate-400
                    text-slate-700
                    px-8
                    py-4
                    rounded-full
                    font-semibold
                "
            >
                Batal
            </a>

        </div>

    </form>

</div>

</body>
</html>