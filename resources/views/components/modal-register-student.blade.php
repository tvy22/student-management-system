<div x-show="showRegisterModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    <div x-show="showRegisterModal" x-transition.opacity @click="showRegisterModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showRegisterModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-blue-300 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Register Student</h2>
                </div>
                <button @click="showRegisterModal = false" class="p-2 bg-white text-gray-400 hover:text-gray-600 rounded-xl shadow-sm border border-gray-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form action="#" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                {{-- Picture Upload Area (Full Width) --}}
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl p-6 bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer relative">
                    <input type="file" name="profile_pic" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-wider">Upload Student Photo</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="space-y-6">
                        {{-- Full Name --}}
                        <div>
                            <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700">
                            @error('name')
                                <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Gender</label>
                            <div class="relative">
                                <select name="gender" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold appearance-none text-slate-700">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </div>
                                @error('gender')
                                    <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- Phone Number --}}
                        <div>
                            <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Phone Number</label>
                            <input type="text" name="phone" placeholder="012 345 678" value="{{ old('phone') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700">
                            @error('phone')
                                <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Assign to Class --}}
                        <div>
                            <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Assign to Class</label>
                            <div class="relative">
                                <select name="class_id" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold appearance-none text-slate-700">
                                    <option value="">Select a class</option>
                                    <option value="1">Advanced Mathematics</option>
                                    <option value="2">Physics 101</option>
                                    <option value="3">World History</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </div>
                                @error('class_id')
                                    <span class="text-[10px] font-black text-red-500 uppercase tracking-tight ml-2 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] cursor-pointer">
                    Complete Registration
                </button>
            </form>
        </div>
    </div>
</div>
