<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ env('APP_NAME') }} | API register </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <form action="{{ route('register') }}" method="post">
        @csrf
        <div class="flex flex-col items-center justify-center bg-yellow-300 h-screen">
            <div class="xl:w-[1200px] w-full flex flex-col items-center bg-white/30 backdrop-blur-sm border border-white/20 rounded-lg py-12 px-6 space-y-4">
                <h1 class="text-2xl font-semibold">S'inscrire à l'API Tickerama</h1>
                
                @if (session('success'))
                    <div class="bg-green-600 w-full text-white text-lg px-3 py-4">
                         {{ session('success') }}
                    </div>
                @endif
                <div class="flex space-x-3 w-full">
                    <div class="flex flex-col w-1/2">
                        <label for="firstname" class="text-lg">Nom</label>
                        <input type="text" name="firstname" class="border border-gray-400 outline-yellow-500 py-2 px-3 rounded-md">
                        @error('firstname')
                            <p class="text-red-600"> {{ $message }} </p>
                        @enderror
                    </div>
                    <div class="flex flex-col w-1/2">
                        <label for="lastname" class="text-lg">Prénom</label>
                        <input type="text" name="lastname" class="border border-gray-400 outline-yellow-500 py-2 px-3 rounded-md">
                        @error('lastname')
                            <p class="text-red-600"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>
                <div class="flex space-x-3 w-full">
                    <div class="flex flex-col w-2/5">
                        <label for="business" class="text-lg">Entreprise</label>
                        <input type="text" name="business" class="border border-gray-400 outline-yellow-500 py-2 px-3 rounded-md">
                        @error('business')
                            <p class="text-red-600"> {{ $message }} </p>
                        @enderror
                    </div>
                    <div class="flex flex-col w-3/5">
                        <label for="email" class="text-lg">Email</label>
                        <input type="email" name="email" class="border border-gray-400 outline-yellow-500 py-2 px-3 rounded-md">
                        @error('email')
                            <p class="text-red-600"> {{ $message }} </p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col w-full">
                    <label for="town" class="text-lg">Ville</label>
                    <input type="text" name="town" class="border border-gray-400 outline-yellow-500 py-2 px-3 rounded-md">
                    @error('town')
                        <p class="text-red-600"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="flex flex-col w-full">
                    <label for="address" class="text-lg">Adresse</label>
                    <textarea type="text" name="address" class="border border-gray-400 outline-yellow-500 py-2 px-3 rounded-md"></textarea>
                    @error('town')
                        <p class="text-red-600"> {{ $message }} </p>
                    @enderror
                </div>
                <div class="w-full flex flex-row-reverse">
                    <button class="bg-yellow-500 w-full mt-12 py-3 text-white text-sm rounded-md px-3 py-3">
                        Je m'enregister
                    </button>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
