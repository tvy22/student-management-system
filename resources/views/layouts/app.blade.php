{{-- sidebar + header --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkillTech Dashboard</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-gradient {
            background: linear-gradient(135deg, #59D3FC 0%, #554DDE 100%);
        }
        html, body { height: 100%; margin: 0; overflow: hidden; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased font-sans bg-gray-50 text-gray-900"
      x-data="{
        open: false,
        showLogoutModal: false,
        user: (JSON.parse(localStorage.getItem('user_data')) || { user: { name: 'User' } }).user,

        async handleLogout() {
              try {
                  const token = localStorage.getItem('school_token');

                  // 1. Tell the backend to kill the token
                  await fetch('http://localhost:8000/api/logout', {
                      method: 'POST',
                      headers: {
                          'Authorization': `Bearer ${token}`,
                          'Accept': 'application/json',
                          'Content-Type': 'application/json'
                      }
                  });

                  // 2. Clear browser memory
                  localStorage.removeItem('school_token');
                  localStorage.removeItem('user_data');

                  // 3. Redirect to login
                  window.location.href = '/';
              } catch (error) {
                  console.error('Logout error:', error);
                  // Force clear and redirect even if server is down
                  localStorage.clear();
                  window.location.href = '/';
              }
          }

        }">

    <div class="flex h-screen overflow-hidden">

        {{-- sidebar --}}
        <aside
            :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-white flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 rounded-r-2xl shadow-xl"
        >

            {{-- logo --}}
            <div class="flex items-center gap-3 p-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-linear-to-tr from-blue-600 to-indigo-500 rounded-xl shadow-lg backdrop-blur-md transform -rotate-3 border border-white/20 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 14.654l7.74-4.507m-15.48 0L12 5.639l7.74 4.508m-15.48 0v4.597a1.5 1.5 0 00.75 1.299l7.5 4.365a1.5 1.5 0 001.5 0l7.5-4.365a1.5 1.5 0 00.75-1.299v-4.597M12 14.654V21" />
                    </svg>
                </div>

                <h1 class="text-2xl font-black tracking-tight text-gray-900 leading-none">
                    Skill<span class="text-blue-600">Tech</span>
                </h1>
            </div>

            {{-- teacher profile --}}
            <div class="px-6 mb-6">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/20 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#554DDE] font-bold shadow-sm">
                    <img :src="`https://ui-avatars.com/api/?name=${user.name}&background=ffffff&color=554DDE`"
                        :alt="user.name"
                        class="w-10 h-10 rounded-xl object-cover shadow-sm">
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate text-white" x-text="user.name"></p>
                        <p class="text-[10px] font-bold uppercase opacity-70 text-blue-50" x-text="'ID: ' + user.id">ID: </p>
                    </div>
                </div>
            </div>

            {{-- menu items --}}
            <nav class="grow px-4 space-y-3">
                {{-- Home --}}
                <a href="/dashboard"
                   class="group flex items-center gap-4 px-5 py-3 transition-all duration-200 transform active:scale-95 rounded-xl font-bold {{ request()->is('dashboard') ? 'bg-white text-[#554DDE] shadow-xl scale-[1.02]' : 'text-white/90 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Home
                </a>

                {{-- Classes --}}
                <a href="/class"
                   class="group flex items-center gap-4 px-5 py-3 transition-all duration-200 transform active:scale-95 rounded-xl font-bold {{ request()->is('class') ? 'bg-white text-[#554DDE] shadow-xl scale-[1.02]' : 'text-white/90 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    Classes
                </a>

                {{-- Students --}}
                <a href="/students"
                   class="group flex items-center gap-4 px-5 py-3 transition-all duration-200 transform active:scale-95 rounded-xl font-bold {{ request()->is('students') ? 'bg-white text-[#554DDE] shadow-xl scale-[1.02]' : 'text-white/90 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Students
                </a>

                {{-- Attendances --}}
                <a href="/attendances"
                   class="group flex items-center gap-4 px-5 py-3 transition-all duration-200 transform active:scale-95 rounded-xl font-bold {{ request()->is('attendances') ? 'bg-white text-[#554DDE] shadow-xl scale-[1.02]' : 'text-white/90 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.375M9 18h3.375m2.25-13.5h1.125a2.25 2.25 0 0 1 2.25 2.25v13.5a2.25 2.25 0 0 1-2.25 2.25h-11.25a2.25 2.25 0 0 1-2.25-2.25V6.75a2.25 2.25 0 0 1 2.25-2.25h1.125m.75-1.5h7.5a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3.75a.75.75 0 0 1 .75-.75Z" />
                    </svg>
                    Attendances
                </a>

                {{-- About Us --}}
                <a href="/about"
                   class="group flex items-center gap-4 px-5 py-3 transition-all duration-200 transform active:scale-95 rounded-xl font-bold {{ request()->is('about') ? 'bg-white text-[#554DDE] shadow-xl scale-[1.02]' : 'text-white/90 hover:text-white hover:bg-white/10 hover:translate-x-1' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    About Us
                </a>
            </nav>

            {{-- logout --}}
            <div class="p-6">
                <button @click="showLogoutModal = true" class="w-full flex items-center gap-4 px-5 py-3 bg-linear-to-r from-indigo-600 to-purple-500 hover:from-red-500 hover:to-red-600 text-white rounded-xl font-bold transition-all duration-200 border border-white/10 shadow-lg cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Logout
                </button>
            </div>
        </aside>

        {{-- mobile overlay --}}
        <div x-show="open" @click="open = false" class="fixed inset-0 bg-blue-900/40 backdrop-blur-sm z-40 lg:hidden" x-transition.opacity></div>

        {{-- header + content --}}
        <main class="flex-1 flex flex-col min-w-0 bg-white overflow-y-auto">
            <header class="bg-white border-b border-gray-100 p-4 lg:p-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="open = !open" class="p-2 bg-blue-50 text-blue-600 rounded-lg lg:hidden cursor-pointer hover:bg-blue-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class=" text-xl lg:text-3xl font-black text-indigo-900 uppercase tracking-tight">Student Management System</h1>
                </div>
            </header>

            <div class="p-4 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- import logout modal --}}
    <x-modal-logout/>

    {{-- import notification --}}
    <x-notification/>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: "{{ session('success') }}", type: 'success' }
                }));
            @endif

            @if(session('error'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: "{{ session('error') }}", type: 'error' }
                }));
            @endif

            @if($errors->any())
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: "Please check the form for errors.", type: 'error' }
                }));
            @endif
        });
    </script>

</body>
</html>
