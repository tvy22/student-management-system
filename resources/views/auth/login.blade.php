{{-- Entry Page/ Login Form --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-100 flex items-center justify-center min-h-screen p-4 sm:p-6"
    {{-- 1. Initialize Alpine Data for the whole page --}}
    x-data="{
        email: '',
        password: '',
        errorMessage: '',
        loading: false,
        async handleLogin() {
            this.loading = true;
            this.errorMessage = '';
            try {
                await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
                    credentials: 'include'
                });

                const getCookie = (name) => {
                    let value = ';' + document.cookie;
                    let parts = value.split(';' + name + '=');
                    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
                };

                const xsrfToken = getCookie('XSRF-TOKEN');

                const response = await fetch('http://127.0.0.1:8000/api/login', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-XSRF-TOKEN': xsrfToken
                    },
                    body: JSON.stringify({ email: this.email, password: this.password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Save session to browser memory
                    localStorage.setItem('school_token', data.token);
                    localStorage.setItem('user_data', JSON.stringify(data.data));
                    // Redirect to your dashboard
                    window.location.href = '/dashboard';
                } else {
                    this.errorMessage = data.message || 'Invalid email or password.';
                }
            } catch (err) {
                this.errorMessage = 'Connection failed.';
            } finally {
                this.loading = false;
            }
        }
    }">

    <div class="bg-white w-full max-w-5xl min-h-150 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        {{-- left side --}}
        <div class="hidden lg:block lg:w-1/2 relative">
            <img src="{{ asset('images/login-bg.jpg') }}" alt="Education" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-blue-600/20 mix-blend-multiply"></div>
            <div class="absolute bottom-12 left-12 right-12 text-white z-10">
                <h2 class="text-4xl font-bold leading-tight">Student Management System</h2>
                <p class="text-lg opacity-90 mt-2">Track your students’ attendance easily.</p>
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
                    <h1 class="text-4xl font-black tracking-tight text-gray-900">Skill<span class="text-blue-600">Tech</span></h1>
                </div>

                {{-- 2. Use @submit.prevent to trigger our JS function --}}
                <form @submit.prevent="handleLogin" class="space-y-5">

                    {{-- 3. Dynamic Error Message Display --}}
                    <div x-show="errorMessage" x-transition
                         class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl text-sm font-bold">
                        <p x-text="errorMessage"></p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1 ml-1">Email</label>
                        {{-- 4. Added x-model="email" --}}
                        <input x-model="email" type="email" name="email" required
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="name@gmail.com">
                    </div>

                    <div>
                        <div class="flex justify-between mb-1 ml-1">
                            <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                        </div>
                        {{-- 5. Added x-model="password" --}}
                        <input x-model="password" type="password" name="password" required
                            class="w-full px-5 py-3 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 outline-none shadow-sm placeholder:text-gray-400"
                            placeholder="••••••••">
                    </div>

                    {{-- 6. Dynamic Button with Loading State --}}
                    <button type="submit" :disabled="loading"
                        class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-blue-200 hover:shadow-blue-300 transform transition active:scale-[0.98] duration-200 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span x-show="!loading">Log In</span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </button>

                    <p class="text-center text-gray-500 text-sm">
                        Don't have an account?
                        <a href="/register" class="text-blue-600 font-bold hover:underline underline-offset-4">Register</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
