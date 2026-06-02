<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f6f1eb] flex items-center justify-center min-h-screen">

    <form
        action="/register"
        method="POST"
        class="bg-white p-10 rounded-[35px] shadow-xl w-[450px]"
    >

        @csrf

        <h1 class="text-4xl font-black mb-8 text-orange-500">
            Register
        </h1>

        <input
            type="text"
            name="name"
            placeholder="Nama"
            class="w-full border p-4 rounded-2xl mb-5"
        >

        <input
            type="email"
            name="email"
            placeholder="Email"
            class="w-full border p-4 rounded-2xl mb-5"
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            class="w-full border p-4 rounded-2xl mb-5"
        >

        <button
            class="w-full bg-orange-500 text-white py-4 rounded-2xl"
        >
            Register
        </button>

    </form>

</body>
</html>