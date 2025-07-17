<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceramic Styles - Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-2xl rounded-xl w-full max-w-4xl flex overflow-hidden">
        <div class="w-1/2 bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-10 flex flex-col justify-center">
            <h2 class="text-4xl font-bold mb-4">Welcome to</h2>
            <h1 class="text-5xl font-extrabold mb-6">Spencera Styles</h1>
            <p class="text-lg">Discover stylish and durable ceramic products for every space.</p>
        </div>

        <div class="w-1/2 p-10">
            <div class="flex justify-end space-x-4 mb-6">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-semibold">Login</a>
            </div>

            <h2 class="text-2xl font-bold mb-4">Forgot to your account password</h2>

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div> -->
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="w-full text-center bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
