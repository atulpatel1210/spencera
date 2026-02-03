<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spencera - Forgot Password</title>
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
        <!-- Left Side: Branding (Hidden on mobile) -->
        <div class="hidden md:flex md:w-1/2 luxury-gradient flex-col justify-center items-center p-12 text-white">
            <div class="mb-8 transform hover:scale-105 transition-transform duration-500">
                <img src="https://spenceraceramica.com/images/logo/white.svg" alt="Spencera Logo" class="h-24">
            </div>
            <div class="text-center max-w-sm">
                <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Recover Access</h1>
                <p class="text-gray-400 font-light leading-relaxed">
                    Don't worry, it happens to the best of us. Let's get you back into your account.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="md:w-1/2 p-8 md:p-14 bg-white/90">
            <div class="flex flex-col h-full">
                <!-- Mobile Logo -->
                <div class="md:hidden flex justify-center mb-10">
                    <img src="https://spenceraceramica.com/images/logo/black.svg" alt="Spencera Logo" class="h-14">
                </div>

                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-900">Forgot Password?</h2>
                    <div class="h-1 w-12 bg-black mt-2 mx-auto md:mx-0"></div>
                </div>

                <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium text-center md:text-left">
                    Enter your email address and we'll send you a link to reset your password.
                </p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-8 p-4 rounded-2xl bg-green-50 border border-green-100 text-green-700 text-sm flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                        <div class="group relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-black transition-colors">
                                <i class="far fa-envelope text-sm"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-800 focus:bg-white focus:outline-none transition-all input-focus"
                                placeholder="Enter registered email">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-red-500 font-medium italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-sm tracking-[0.2em] uppercase hover:bg-gray-800 transform transition-all active:scale-[0.98] shadow-lg flex items-center justify-center">
                            Send Reset Link <i class="fas fa-paper-plane ml-3 text-xs opacity-70"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-auto pt-12 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-black transition-colors flex items-center justify-center group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>