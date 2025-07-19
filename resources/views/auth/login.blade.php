    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spencera - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    </head>

    <body class="bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-2xl rounded-xl w-full max-w-4xl flex overflow-hidden">
            <!-- <div class="w-1/2 bg-gradient-to-br from-black-600 to-purple-600 text-white p-10 flex flex-col justify-center"> -->
            <div class="w-1/2 bg-black text-white p-10 flex flex-col justify-center">
                <div class="flex justify-center">
                    <img src="https://spenceraceramica.com/images/logo/white.svg" alt="Spencera Styles Logo" class="h-24">
                </div>
            </div>

            <div class="w-1/2 p-10">
                <div class="flex justify-end space-x-4 mb-6">
                    <a href="{{ route('register') }}" class="text-black-600 font-semibold bg-btn-login">Register</a>
                </div>

                <h2 class="text-2xl font-bold mb-4">Login to your account</h2>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full w-full mt-1 px-4 py-2 border rounded-lg focus:black-none focus:ring-2 focus:ring-black-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full w-full mt-1 px-4 py-2 border rounded-lg focus:black-none focus:ring-2 focus:ring-black-500"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex justify-between items-center">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="form-checkbox text-black-600" name="remember">
                            <span class="ms-2 text-sm text-gray-600">&nbsp;&nbsp;{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-black dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:black-none focus:ring-2 focus:ring-offset-2 focus:ring-black-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="w-full text-center bg-black text-white py-2 rounded-lg font-semibold hover:bg-black-700 transition duration-300">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </body>

    </html>
