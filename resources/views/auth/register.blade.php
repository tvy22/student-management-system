{{-- Register Form --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MySMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-100 flex items-center justify-center min-h-screen p-4 sm:p-6">

    <div class="bg-white w-full max-w-5xl min-h-150 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row">

        {{-- left side --}}
        <div class="hidden lg:block lg:w-1/2 relative">
            <img
                src="{{ asset('images/login-bg.jpg') }}"
                alt="Education"
                class="absolute inset-0 w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-blue-600/20 mix-blend-multiply"></div>

            <div class="absolute bottom-12 left-12 right-12 text-white z-10">
                <h2 class="text-4xl font-bold leading-tight">Join SkillTech Today</h2>
                <p class="text-lg opacity-90 mt-2">Start managing your classroom attendance with ease.</p>
            </div>
        </div>

        {{-- right side --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md">

                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-linear-to-tr from-blue-600 to-indigo-500 rounded-2xl shadow-xl mb-6 transform -rotate-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 14.654l7.74-4.507m-15.48 0L12 5.639l7.74 4.508m-15.48 0v4.597a1.5 1.5 0 00.75 1.299l7.5 4.365a1.5 1.5 0 001.5 0l7.5-4.365a1.5 1.5 0 00.75-1.299v-4.597M12 14.654V21" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-black tracking-tight text-gray-900">
                        Skill<span class="text-blue-600">Tech</span>
                    </h1>
                </div>

                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1 ml-1">Full Name</label>
                        <input
                            type="text"
                            name="name"
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="John Doe"
                            value="{{ old(key: 'name') }}"
                        >
                        @error('name')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1 ml-1">Teacher Email</label>
                        <input
                            type="email"
                            name="email"
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="name@skilltech.com"
                            value="{{ old(key: 'email') }}"
                        >
                        @error('email')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-1 ml-1">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-blue-200 hover:shadow-blue-300 transform transition active:scale-[0.98] duration-200"
                    >
                        Create Account
                    </button>

                    <p class="text-center text-gray-500 text-sm mt-4">
                        Already have an account?
                        <a href="/" class="text-blue-600 font-bold hover:underline underline-offset-4">Log In</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
