<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spencera - Reset Password</title>
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
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 bg-auth">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/40 z-0"></div>

    <div class="relative z-10 w-full max-w-lg shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-3xl overflow-hidden glass-effect p-8 md:p-14">
        <div class="text-center mb-10">
            <div class="inline-block p-4 bg-black rounded-2xl mb-6 shadow-xl">
                <img src="https://spenceraceramica.com/images/logo/white.svg" alt="Spencera Logo" class="h-10">
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
            <p class="text-gray-500 text-sm mt-4 leading-relaxed font-medium">
                Almost there! Secure your account by creating a new password.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                <div class="group relative opacity-75">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="far fa-envelope text-sm"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus readonly
                        class="block w-full pl-12 pr-4 py-4 bg-gray-100 border border-gray-100 rounded-2xl text-gray-500 cursor-not-allowed outline-none"
                        placeholder="Email Address">
                </div>
                @error('email')
                    <p class="mt-2 text-xs text-red-500 font-medium italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">New Password</label>
                <div class="group relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-black transition-colors">
                        <i class="far fa-lock text-sm"></i>
                    </span>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-800 focus:bg-white focus:outline-none transition-all input-focus"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="mt-2 text-xs text-red-500 font-medium italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm New Password</label>
                <div class="group relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-black transition-colors">
                        <i class="far fa-shield-check text-sm"></i>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-800 focus:bg-white focus:outline-none transition-all input-focus"
                        placeholder="••••••••">
                </div>
                @error('password_confirmation')
                    <p class="mt-2 text-xs text-red-500 font-medium italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-bold text-sm tracking-[0.2em] uppercase hover:bg-gray-800 transform transition-all active:scale-[0.98] shadow-[0_10px_30px_rgba(0,0,0,0.15)] flex items-center justify-center">
                    Reset Password <i class="fas fa-key ml-3 text-xs opacity-70"></i>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
