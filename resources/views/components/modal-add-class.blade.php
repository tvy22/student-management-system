{{-- add class modal, clicked from dashboard.blade --}}

<div x-show="showAddClassModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    <div x-show="showAddClassModal" x-transition.opacity @click="showAddClassModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showAddClassModal" x-transition class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-8 bg-blue-300 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Create New Class</h2>
                    <p class="text-sm font-bold text-white uppercase tracking-tight">Enter class details below</p>
                </div>
                <button @click="showAddClassModal = false" class="p-2 bg-white text-gray-400 hover:text-gray-600 rounded-xl shadow-sm border border-gray-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="#" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Course Name</label>
                    <input type="text" name="name" placeholder="e.g. Mathematics Advanced" value="{{ old('name') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700">
                    @error('name')
                        <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Room</label>
                        <input type="text" name="room" placeholder="302" value="{{ old('room') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                        @error('room')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Term</label>
                        <select name="term" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                            <option>Sat - Sun</option>
                            <option>Mon - Fri</option>
                        </select>
                        @error('term')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Teacher</label>
                        <input type="text" name="teacher" placeholder="Dr. Sarah Jenkins" value="{{ old('teacher') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                        @error('teacher')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Total Hours</label>
                        <input type="text" name="hours" placeholder="200" value="{{ old('hours') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                        @error('hours')
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button @click="$dispatch('notify', { message: 'Class Added!', type: 'success' })" type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] cursor-pointer">
                    Add New Class
                </button>
            </form>

        </div>
    </div>
</div>
