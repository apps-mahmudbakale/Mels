<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Government Projects Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-blue-900 text-white p-4 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <img src="/images/logo.png" class="w-10 h-10 rounded-full">
        <h1 class="text-xl font-bold">Government Analytics Dashboard</h1>
    </div>
</header>

<main class="p-8">
    @yield('content')
</main>

</body>
</html>
