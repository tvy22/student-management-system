{{-- logout modal --}}

<div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    <div x-show="showLogoutModal" x-transition.opacity @click="showLogoutModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4 text-center">
        <div x-show="showLogoutModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 overflow-hidden">

            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <h2 class="text-2xl font-black text-slate-800 mb-2">Ready to Leave, <span x-text="user.name"></span>?</h2>
            <p class="text-slate-400 font-bold mb-8">
                Are you sure you want to log out of your session?
            </p>

            <div class="flex flex-col gap-3">
                <button
                    @click.prevent="handleLogout()"
                    type="button"
                    :disabled="loading"
                    class="w-full py-4 flex items-center justify-center bg-linear-to-r from-indigo-600 to-purple-600 hover:from-red-500 hover:to-red-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-100 transition-all transform active:scale-[0.98] cursor-pointer"
                >
                    <span x-show="!loading">Yes, Logout</span>

                    <div x-show="loading" class="flex items-center gap-2" x-cloak>
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Logging out...</span>
                    </div>

                </button>

                <button
                    type="button"
                    @click="showLogoutModal = false"
                    class="w-full py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition cursor-pointer">
                    Stay Logged In
                </button>
            </div>
        </div>
    </div>
</div>
