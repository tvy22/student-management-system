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
            <form @submit.prevent="updateClass()" class="p-8 space-y-5">

                <div class="grid grid-cols-2 gap-4 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 text-left">Course Name</label>
                        <select x-model="editFormData.course" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                            <option>React+Laravel</option>
                            <option>HTML/CSS/JavaScript</option>
                            <option>MySql</option>
                            <option>C++</option>
                            <option>Python</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Room</label>
                        <select x-model="editFormData.room" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                            <option>A101</option>
                            <option>B303</option>
                            <option>C120</option>
                            <option>A204</option>
                            <option>B402</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-left">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Term</label>
                        <select x-model="editFormData.term" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                            <option>Sat-Sun</option>
                            <option>Mon-Fri</option>
                            <option>Mon-Thu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Class Time</label>
                        <select x-model="editFormData.class_time" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                            <option>9:00-10:30am</option>
                            <option>11:00-12:30am</option>
                            <option>1:00-2:30pm</option>
                            <option>3:00-4:30pm</option>
                            <option>5:00-6:30pm</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showEditClassModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition cursor-pointer">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="flex-2 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl shadow-lg shadow-amber-200 transition-all transform active:scale-[0.98] cursor-pointer"
                    >
                        <span x-show="!loading">Save Changes</span>

                        <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
