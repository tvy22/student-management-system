
<div x-show="showEditClassModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    {{-- Backdrop --}}
    <div x-show="showEditClassModal" x-transition.opacity @click="showEditClassModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showEditClassModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-amber-50 border-b border-amber-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-amber-900">Edit Class</h2>
                    <p class="text-sm font-bold text-amber-700/60 uppercase tracking-tight">Update Class Information</p>
                </div>
                <button @click="showEditClassModal = false" class="p-2 bg-white text-amber-400 hover:text-amber-600 rounded-xl shadow-sm border border-amber-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form action="#" method="POST" class="p-8 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 text-left">Class Name</label>
                    <input type="text" name="name" value="Mathematics Advanced" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                    @error('name')
                        <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Room</label>
                        <input type="text" name="room" value="Room 302" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        @error('room')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Term</label>
                        <input type="text" name="term" value="Sat - Sun" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        @error('term')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-left">
                    <div class="text-left">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Teacher Name</label>
                        <input type="text" name="teacher" value="Dr. Sarah Jenkins" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        @error('teacher')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-left">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Total Hours</label>
                        <input type="text" name="hours" value="200" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        @error('hours')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showEditClassModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex-2 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl shadow-lg shadow-amber-200 transition-all transform active:scale-[0.98] cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
