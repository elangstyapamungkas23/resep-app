<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Desain kustom opsional agar scrollbar terlihat lebih tipis dan estetik */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-100">

<div class="max-w-7xl mx-auto py-10">

    <div class="flex justify-between items-center mb-10">

        <h1 class="text-5xl font-black">
            Dashboard Admin
        </h1>

        <a
            href="/"
            class="bg-orange-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-orange-600"
        >
            ← Kembali ke Home
        </a>

    </div>

    <div class="grid grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-6 rounded-3xl shadow">
            <h2 class="text-xl font-bold">👤 User</h2>
            <p class="text-4xl font-black mt-2">
                {{ $totalUser }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow">
            <h2 class="text-xl font-bold">🍜 Resep</h2>
            <p class="text-4xl font-black mt-2">
                {{ $totalResep }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow">
            <h2 class="text-xl font-bold">💬 Komentar</h2>
            <p class="text-4xl font-black mt-2">
                {{ $totalKomentar }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow">
            <h2 class="text-xl font-bold">⭐ Rating</h2>
            <p class="text-4xl font-black mt-2">
                {{ $totalRating }}
            </p>
        </div>

    </div>

    <div class="bg-white p-6 rounded-3xl shadow mb-8">

        <h2 class="text-2xl font-bold mb-4">
            Resep Terbaru
        </h2>

        <div class="max-h-[300px] overflow-y-auto block w-full pr-2">
            <table class="w-full border-collapse">

                <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(226,232,240,1)]">
                    <tr>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            Nama Resep
                        </th>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            Pembuat
                        </th>
                        <th class="text-center py-3 bg-white font-bold text-slate-600">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($reseps as $resep)

                    <tr class="border-b hover:bg-slate-50 transition-colors">

                        <td class="py-3 pr-4">
                            {{ $resep->nama_resep }}
                        </td>

                        <td class="pr-4">
                            {{ $resep->user->name }}
                        </td>

                        <td class="text-center py-2">

                            <form
                                action="/reseps/{{ $resep->id }}"
                                method="POST"
                                onsubmit="return confirm('Hapus resep ini?')"
                                class="inline-block"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

    <div class="bg-white p-6 rounded-3xl shadow mb-8">

        <h2 class="text-2xl font-bold mb-4">
            Komentar Terbaru
        </h2>

        <div class="max-h-[300px] overflow-y-auto block w-full pr-2">
            <table class="w-full border-collapse">

                <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(226,232,240,1)]">
                    <tr>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            User
                        </th>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            Komentar
                        </th>
                        <th class="text-center py-3 bg-white font-bold text-slate-600">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($komentars as $komentar)

                    <tr class="border-b hover:bg-slate-50 transition-colors">

                        <td class="py-3 pr-4">
                            {{ $komentar->user->name }}
                        </td>

                        <td class="pr-4">
                            {{ $komentar->komentar }}
                        </td>

                        <td class="text-center py-2">

                            <form
                                action="/komentars/{{ $komentar->id }}"
                                method="POST"
                                onsubmit="return confirm('Hapus komentar ini?')"
                                class="inline-block"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

    <div class="bg-white p-6 rounded-3xl shadow">

        <h2 class="text-2xl font-bold mb-4">
            Daftar User
        </h2>

        <div class="max-h-[300px] overflow-y-auto block w-full pr-2">
            <table class="w-full border-collapse">

                <thead class="sticky top-0 bg-white z-10 shadow-[0_1px_0_0_rgba(226,232,240,1)]">
                    <tr>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            Nama
                        </th>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            Email
                        </th>
                        <th class="text-left py-3 bg-white font-bold text-slate-600">
                            Role
                        </th>
                        <th class="text-center py-3 bg-white font-bold text-slate-600">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($users as $user)

                    <tr class="border-b hover:bg-slate-50 transition-colors">

                        <td class="py-3 pr-4">
                            {{ $user->name }}
                        </td>

                        <td class="pr-4">
                            {{ $user->email }}
                        </td>

                        <td class="pr-4">
                            {{ $user->role }}
                        </td>

                        <td class="text-center py-2">

                            @if($user->id != auth()->id())

                            <form
                                action="/admin/users/{{ $user->id }}"
                                method="POST"
                                onsubmit="return confirm('Hapus user ini?')"
                                class="inline-block"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition"
                                >
                                    Hapus
                                </button>

                            </form>

                            @else

                            <span class="text-slate-400 font-medium text-sm">
                                Akun Saya
                            </span>

                            @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

</div>

</body>
</html>