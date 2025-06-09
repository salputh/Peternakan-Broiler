<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="flex flex-col items-center justify-center min-h-screen py-6 sm:py-12 lg:py-24">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-grey-900">Welcome to Laravel</h1>
            <p class="mt-4 text-lg text-gray-600">Aplikasi ini nantinya akan menjadi <br> tempat untuk implementasi model machine learning yang gue buat</p>
        </div>
    </div>
</body>

</html>