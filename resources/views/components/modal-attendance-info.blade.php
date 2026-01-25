<div
    x-show="showAttendanceInfoModal"
    @keydown.escape.window="showAttendanceInfoModal = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div @click.away="showAttendanceInfoModal = false" class="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden relative">

            <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-linear-to-r from-blue-300 to-emerald-400">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Attendance Details</h2>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">Detailed Record Overview</p>
                </div>
                <button @click="showAttendanceInfoModal = false" class="p-3 bg-slate-100 hover:bg-red-50 hover:text-red-500 text-slate-400 rounded-2xl transition-all cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-linear-to-r from-indigo-500 to-purple-500 rounded-4xl blur opacity-10"></div>
                    <div class="relative bg-white border border-indigo-50 rounded-4xl p-6">
                        <h3 class="text-indigo-900 font-black mb-4 flex items-center gap-2">
                            <span class="p-2 bg-indigo-500 rounded-lg text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                </svg>
                            </span>
                            Student Profile
                        </h3>

                        <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                            <div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase">ID</p>
                                <p class="font-mono font-black text-indigo-600" x-text="'#' + selectedRecord.student_id"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase">Name</p>
                                <p class="font-bold text-slate-700 text-md truncate" x-text="selectedRecord.student_name"></p>
                            </div>

                            <div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase">Phone</p>
                                <p class="font-bold text-slate-600 text-sm" x-text="selectedRecord.student_phone || 'N/A'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase">Email</p>
                                <p class="font-bold text-slate-600 text-sm truncate" x-text="selectedRecord.student_email || 'N/A'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-1 bg-linear-to-r from-sky-400 to-blue-500 rounded-4xl blur opacity-10"></div>
                    <div class="relative bg-white border border-sky-50 rounded-4xl p-6">
                        <h3 class="text-sky-900 font-black mb-4 flex items-center gap-2">
                            <span class="p-2 bg-sky-500 rounded-lg text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.994 7.994 0 005.5 4c-1.255 0-2.435.292-3.48.814l2.535 2.535A3.997 3.997 0 015.5 7c.725 0 1.408.193 1.996.533L9 4.804z"></path>
                                </svg>
                            </span>
                            Class Details
                        </h3>

                        <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                            <div>
                                <p class="text-[10px] font-black text-sky-300 uppercase">Course</p>
                                <p class="font-bold text-slate-800 text-md leading-tight" x-text="selectedRecord.course"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-sky-300 uppercase">Class ID</p>
                                <p class="font-mono font-black text-sky-600" x-text="selectedRecord.class_id"></p>
                            </div>

                            <div>
                                <p class="text-[10px] font-black text-sky-300 uppercase">Room & Term</p>
                                <div class="flex flex-row gap-1 mt-1">
                                    <span class="text-xs font-bold text-slate-600" x-text="'Room: ' + (selectedRecord.room || 'TBD')"></span>
                                    <span class="text-[10px] inline-block bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md w-fit font-black uppercase" x-text="selectedRecord.term"></span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-sky-300 uppercase">Class Time</p>
                                <div class="flex items-center gap-2 text-slate-500 font-bold mt-1">
                                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-sm" x-text="selectedRecord.class_time"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-slate-50 flex justify-end">
                <button @click="showAttendanceInfoModal = false" class="px-8 py-3 bg-slate-800 text-white rounded-xl font-black text-sm cursor-pointer">Close</button>
            </div>
        </div>
    </div>
</div>
