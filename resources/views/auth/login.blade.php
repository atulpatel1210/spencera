<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spencera - Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bg-auth {
            background-image: url('{{ asset('images/auth-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .input-focus:focus {
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
            border-color: #000;
        }
        .luxury-gradient {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 bg-auth">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/40 z-0"></div>

    <div class="relative z-10 w-full max-w-4xl flex flex-col md:flex-row shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-3xl overflow-hidden glass-effect">
        <!-- Left Side: Branding (NO BUTTONS HERE) -->
        <div class="md:w-1/2 luxury-gradient flex flex-col justify-center items-center p-12 text-white">
            <div class="mb-8 transform hover:scale-105 transition-transform duration-500">
                <img src="https://spenceraceramica.com/images/logo/white.svg" alt="Spencera Logo" class="h-24">
            </div>
            <div class="text-center max-w-sm">
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Welcome Back</h1>
                <p class="text-gray-400 font-light leading-relaxed">
                    Elegance is an attitude. Sign in to continue managing your premium ceramic collections.
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 p-8 md:p-14 bg-white/90">
            <div class="flex flex-col h-full">
                <!-- Mobile Logo -->
                <div class="md:hidden flex justify-center mb-10">
                    <img src="https://spenceraceramica.com/images/logo/black.svg" alt="Spencera Logo" class="h-14">
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 text-center md:text-left">Login</h2>
                    <div class="h-1 w-12 bg-black mt-2 mx-auto md:mx-0"></div>
                </div>

                <!-- Session Messages -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('message'))
                    <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 text-sm flex items-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                        <div class="group relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-black transition-colors">
                                <i class="far fa-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-800 focus:bg-white focus:outline-none transition-all input-focus"
                                placeholder="Enter your email">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-red-500 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-gray-400 hover:text-black transition-colors underline decoration-dotted underline-offset-4">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <div class="group relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-black transition-colors">
                                <i class="fas fa-shield-alt text-sm"></i>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-800 focus:bg-white focus:outline-none transition-all input-focus"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-red-500 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 text-black border-gray-200 rounded-lg focus:ring-black/20 focus:ring-offset-0 transition-all cursor-pointer">
                        <label for="remember_me" class="ml-3 block text-sm text-gray-500 font-medium cursor-pointer">Keep me signed in</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-sm tracking-[0.2em] uppercase hover:bg-gray-800 transform transition-all active:scale-[0.98] shadow-lg flex items-center justify-center">
                            Sign In <i class="fas fa-arrow-right ml-3 text-xs opacity-70"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-auto pt-12 text-center">
                    <p class="text-sm text-gray-500 font-medium">
                        New to Spencera? 
                        <a href="{{ route('register') }}" class="text-black font-bold hover:underline underline-offset-4 ml-1">Create an account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
