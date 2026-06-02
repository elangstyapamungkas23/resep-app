<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f6f1eb] min-h-screen">

    <div class="max-w-4xl mx-auto py-12 px-6">

        <div class="bg-white rounded-[40px] shadow-xl p-10">

            <h1 class="text-5xl font-black text-slate-900 mb-3">
                ➕ Tambah Resep Baru
            </h1>

            <p class="text-slate-500 mb-10">
                Bagikan resep terbaikmu ke seluruh pengguna Recipe App.
            </p>

            @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-600 p-4 rounded-2xl mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form
                action="/reseps/store"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                <!-- Nama Resep -->
                <div>
                    <label class="block font-semibold mb-2">
                        Nama Resep
                    </label>

                    <input
                        type="text"
                        name="nama_resep"
                        placeholder="Contoh: Nasi Goreng Spesial"
                        class="w-full border rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    >
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block font-semibold mb-2">
                        Kategori
                    </label>

                    <select
                        name="kategori_id"
                        class="w-full border rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    >
                        <option value="">
                            Pilih Kategori
                        </option>

                        @foreach($kategoris as $kategori)

                        <option value="{{ $kategori->id }}">
                            {{ $kategori->nama_kategori }}
                        </option>

                        @endforeach

                    </select>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block font-semibold mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="4"
                        placeholder="Jelaskan resepmu..."
                        class="w-full border rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    ></textarea>
                </div>

                <!-- Bahan -->
                <div>
                    <label class="block font-semibold mb-2">
                        Bahan-bahan
                    </label>

                    <textarea
                        name="bahan"
                        rows="6"
                        placeholder="Masukkan daftar bahan..."
                        class="w-full border rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    ></textarea>
                </div>

                <!-- Langkah -->
                <div>
                    <label class="block font-semibold mb-2">
                        Langkah Memasak
                    </label>

                    <textarea
                        name="langkah"
                        rows="8"
                        placeholder="Tuliskan langkah memasak..."
                        class="w-full border rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    ></textarea>
                </div>

                <!-- Upload Gambar -->
                <div>
                    <label class="block font-semibold mb-3">
                        Foto Resep
                    </label>

                    <input
                        type="file"
                        name="gambar"
                        accept="image/*"
                        onchange="previewImage(event)"
                        class="w-full border rounded-2xl p-4"
                    >

                    <div class="mt-5">
                        <img
                            id="preview"
                            class="hidden w-full h-80 object-cover rounded-3xl shadow-lg"
                        >
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex gap-4 pt-4">

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
                            transition
                        "
                    >
                        🍜 Simpan Resep
                    </button>

                    <a
                        href="/reseps"
                        class="
                            bg-slate-200
                            hover:bg-slate-300
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

    </div>

<script>
function previewImage(event)
{
    const preview = document.getElementById('preview');

    preview.src = URL.createObjectURL(event.target.files[0]);

    preview.classList.remove('hidden');
}
</script>

</body>
</html>