{{-- end class modal --}}

<div x-show="showEndClassModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    <div x-show="showEndClassModal" x-transition.opacity @click="showEndClassModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4 text-center">
        <div x-show="showEndClassModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 overflow-hidden">

            {{-- Warning Icon --}}
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h2 class="text-2xl font-black text-slate-800 mb-2">End this Class?</h2>
            <p class="text-slate-400 font-bold mb-8">
                This will move <span class="text-slate-800">Mathematics Advanced</span> to the archives. You won't be able to take new attendance for it.
            </p>

            {{-- Action Buttons --}}
            <form action="#" method="POST" class="flex flex-col gap-3">
                @csrf
                @method('DELETE')

                <button type="submit" class="w-full py-4 bg-red-500 hover:bg-red-600 text-white font-black rounded-2xl shadow-lg shadow-red-100 transition-all transform active:scale-[0.98] cursor-pointer">
                    Yes, End Class
                </button>

                <button type="button" @click="showEndClassModal = false" class="w-full py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition cursor-pointer">
                    Cancel
                </button>
            </form>
        </div>
    </div>
</div>
