<div
    x-show="showTakeAttendanceModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;"
>
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4 md:p-8">
        <div class="bg-slate-50 w-full max-w-7xl rounded-[3rem] shadow-2xl overflow-hidden border border-white relative">

            <div class="p-0 max-h-[90vh] flex flex-col">

                <div class="sticky top-0 z-20 bg-slate-50 p-8 md:p-12 pb-4 shadow-sm ">
                    {{-- Header Section --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div class="flex items-center gap-4">
                            <button @click="showTakeAttendanceModal = false" class="p-3 bg-white border border-gray-100 rounded-2xl text-slate-400 hover:text-red-600 hover:border-red-100 transition shadow-sm cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daily Attendance</h1>
                                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest" x-text="classInfo.course_name"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="date" x-model="attendanceDate" class="px-5 py-3 bg-white border-2 border-gray-100 rounded-2xl font-bold text-slate-700 outline-none focus:border-blue-500 transition shadow-sm">
                            <button @click="submitAttendance()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-blue-200 transition active:scale-95 flex items-center gap-2 cursor-pointer">
                                Save Attendance
                            </button>
                        </div>
                    </div>

                    {{-- Quick Tools --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-4xl p-4 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3 px-4">
                            <div class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
                            <p class="text-indigo-900 font-bold text-sm">Marking attendance for all students</p>
                        </div>
                        <button @click="$dispatch('mark-all-present')" class="px-6 py-2.5 bg-white text-indigo-600 font-black text-xs uppercase tracking-widest rounded-xl shadow-sm hover:bg-indigo-600 hover:text-white transition-all active:scale-95 cursor-pointer">
                            Mark All Present
                        </button>
                    </div>
                </div>

                {{-- 2. Scrollable Table Area --}}
                <div class="p-8 md:p-12 pt-0 overflow-y-auto grow">
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-4">
                        <table class="w-full text-left border-collapse">
                            {{-- 3. Sticky Table Header --}}
                            <thead class="sticky top-0 z-10">
                                <tr class="bg-slate-800 border-b border-slate-700">
                                    <th class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest">Student Information</th>
                                    <th class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center">Status</th>
                                    <th class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest">Remark</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="student in students" :key="student.id">
                                    <tr class="attendance-row hover:bg-slate-50/50 transition"
                                        :data-student-id="student.id"
                                        x-data="{ status: 'present', note: '' }"
                                        @mark-all-present.window="status = 'present'">

                                        <td class="p-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-sm"
                                                    x-text="student.name.split(' ').map(n => n[0]).join('').toUpperCase()">
                                                </div>
                                                <div>
                                                    <p class="font-black text-slate-800 text-lg transition" x-text="student.name"></p>
                                                    <p class="text-xs font-bold text-slate-400 uppercase" x-text="'ID: ' + student.id"></p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="p-6 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button @click="status = 'present'" :class="status === 'present' ? 'bg-green-500 text-white shadow-lg shadow-green-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Present</button>
                                                <button @click="status = 'absent'" :class="status === 'absent' ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Absent</button>
                                                <button @click="status = 'permission'" :class="status === 'permission' ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Permission</button>
                                            </div>
                                        </td>

                                        <td class="p-6">
                                            <input type="text"
                                                x-model="note"
                                                placeholder="Add reason..."
                                                :disabled="status !== 'permission'"
                                                :class="status === 'permission' ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed'"
                                                class="w-full px-4 py-3 border-2 rounded-xl text-sm font-bold transition-all outline-none focus:border-blue-500">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
