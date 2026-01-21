{{-- Register Form --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - SkillTech</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-100 flex items-center justify-center min-h-screen p-4 sm:p-6"
    {{-- 1. Matches Login Style: Initialize Alpine Data --}}
    x-data="{
        name: '',
        email: '',
        password: '',
        errorMessage: '',
        loading: false,
        async handleRegister() {
            this.loading = true;
            this.errorMessage = '';
            try {
                const response = await fetch('http://localhost:8000/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.name,
                        email: this.email,
                        password: this.password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // On success, redirect to login (or you can auto-login them)
                    window.location.href = '/';
                } else {
                    // Handle Laravel validation errors or existing user
                    this.errorMessage = data.message || 'Registration failed. Please check your details.';
                }
            } catch (err) {
                this.errorMessage = 'Connection failed.';
            } finally {
                this.loading = false;
            }
        }
    }">

    <div class="bg-white w-full max-w-5xl min-h-150 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row">

        {{-- Left Side --}}
        <div class="hidden lg:block lg:w-1/2 relative">
            <img src="{{ asset('images/login-bg.jpg') }}" alt="Education" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-blue-600/20 mix-blend-multiply"></div>

            <div class="absolute bottom-12 left-12 right-12 text-white z-10">
                <h2 class="text-4xl font-bold leading-tight">Join SkillTech Today</h2>
                <p class="text-lg opacity-90 mt-2">Start managing your classroom attendance with ease.</p>
            </div>
        </div>

        {{-- Right Side --}}
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

                {{-- Trigger handleRegister on submit --}}
                <form @submit.prevent="handleRegister" class="space-y-4">

                    {{-- Error Display --}}
                    <div x-show="errorMessage" x-transition
                         class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl text-sm font-bold">
                        <p x-text="errorMessage"></p>
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Full Name</label>
                        <input x-model="name" type="text" required
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="John Doe">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Email</label>
                        <input x-model="email" type="email" required
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="name@gmail.com">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Password</label>
                        <input x-model="password" type="password" required
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="••••••••">
                    </div>

                    {{-- Button with Loading State --}}
                    <button type="submit" :disabled="loading"
                        class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-blue-200 hover:shadow-blue-300 transform transition active:scale-[0.98] duration-200 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span x-show="!loading">Create Account</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
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
