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
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-semibold">Register</a>
                </div>

                <h2 class="text-2xl font-bold mb-4">Login to your account</h2>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex justify-between items-center">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="form-checkbox text-indigo-600" name="remember">
                            <span class="ms-2 text-sm text-gray-600">&nbsp;&nbsp;{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="w-full text-center bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>
