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
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4 md:p-8">
        <div class="bg-slate-50 w-full max-w-7xl rounded-[3rem] shadow-2xl overflow-hidden border border-white/20 relative">

            <div class="p-0 max-h-[90vh] flex flex-col">

                {{-- Header Section --}}
                <div class="sticky top-0 z-20 bg-slate-50/80 backdrop-blur-xl p-6 md:p-10 pb-6 border-b border-slate-200/60">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
                        <div class="flex items-center gap-5">
                            <button @click="showTakeAttendanceModal = false" class="p-3.5 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition-all shadow-sm cursor-pointer active:scale-90">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daily Attendance</h1>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-[10px] font-black uppercase tracking-wider" x-text="classInfo.course_name"></span>
                                    <span class="text-slate-300">•</span>
                                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Marking Session</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <input type="date" x-model="attendanceDate" readonly class="pl-5 pr-10 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-600 outline-none shadow-sm cursor-default">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-3 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <button @click="submitAttendance()"
                                    :disabled="loading"
                                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 text-white font-black py-3.5 px-8 rounded-2xl shadow-xl shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2 cursor-pointer border-b-4 border-blue-800">
                                <template x-if="!loading">
                                    <span>Save</span>
                                </template>
                                <template x-if="loading">
                                    <div class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span>Saving...</span>
                                    </div>
                                </template>
                            </button>
                        </div>
                    </div>

                    {{-- Quick Tools --}}
                    <div class="bg-white border border-slate-200 rounded-4xl p-3 flex flex-wrap items-center justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3 px-4">
                            <div class="flex -space-x-2">
                                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            </div>
                            <p class="text-slate-600 font-bold text-sm tracking-tight">Active Session: <span class="text-slate-900" x-text="students.length + ' Students'"></span></p>
                        </div>
                        <button @click="$dispatch('mark-all-present')" class="px-6 py-2.5 bg-slate-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg hover:bg-blue-600 transition-all active:scale-95 cursor-pointer">
                            Mark All Present
                        </button>
                    </div>
                </div>

                {{-- Scrollable Table Area --}}
                <div class="p-6 md:p-10 pt-4 overflow-y-auto grow">
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden mb-4">

                        <template x-if="students.length > 0">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100">
                                        <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Student Details</th>
                                        <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Attendance Status</th>
                                        <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reason / Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <template x-for="(student, index) in students" :key="student.id">
                                        <tr class="attendance-row group hover:bg-blue-50/30 transition-all duration-200 border-l-4 border-transparent hover:border-blue-500"
                                            :data-student-id="student.id"
                                            x-data="{
                                                {{-- This is the magic part: it syncs with the parent 'students' array --}}
                                                status: student.today_record ? student.today_record.status : 'present',
                                                note: student.today_record ? student.today_record.remark : ''
                                            }"
                                            {{-- Watch for changes and update the parent array immediately --}}
                                            x-init="$watch('status', v => { if(!student.today_record) student.today_record = {}; student.today_record.status = v })
                                                    $watch('note', v => { if(!student.today_record) student.today_record = {}; student.today_record.remark = v })"
                                            @mark-all-present.window="status = 'present'">

                                            <td class="p-6">
                                                <div class="flex items-center gap-5">
                                                    <div class="relative">
                                                        <div class="w-14 h-14 bg-slate-800 text-white rounded-2xl flex items-center justify-center font-black text-sm shadow-md"
                                                            x-text="student.name.split(' ').map(n => n[0]).join('').toUpperCase()">
                                                        </div>
                                                        {{-- Indicator that this student has a record saved --}}
                                                        <template x-if="student.today_record">
                                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                                        </template>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-slate-800 text-lg leading-tight" x-text="student.name"></p>
                                                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mt-1" x-text="'ID: ' + student.id"></p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="p-6 text-center">
                                                <div class="inline-flex bg-slate-100 p-1.5 rounded-3xl border border-slate-200/50">
                                                    <button @click="status = 'present'" :class="status === 'present' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all cursor-pointer">Present</button>
                                                    <button @click="status = 'absent'" :class="status === 'absent' ? 'bg-white text-red-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all cursor-pointer">Absent</button>
                                                    <button @click="status = 'permission'" :class="status === 'permission' ? 'bg-white text-amber-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:text-slate-600'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all cursor-pointer">Permit</button>
                                                </div>
                                            </td>

                                            <td class="p-6">
                                                <input type="text"
                                                    x-model="note"
                                                    placeholder="Add a remark..."
                                                    :disabled="status === 'present'"
                                                    :class="status !== 'present' ? 'bg-white border-slate-200 text-slate-700 focus:ring-4 focus:ring-blue-50' : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'"
                                                    class="w-full px-5 py-3 border-2 rounded-2xl text-xs font-bold transition-all outline-none">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>

                        {{-- Empty State --}}
                        <template x-if="students.length === 0">
                            <div class="py-32 flex flex-col items-center justify-center text-center px-6">
                                <div class="w-24 h-24 bg-slate-100 rounded-[2.5rem] flex items-center justify-center text-slate-300 mb-6 border-4 border-white shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-slate-800">Class Roster Empty</h3>
                                <p class="text-slate-400 font-bold mt-2 max-w-sm mx-auto text-sm uppercase tracking-wide">
                                    No students found for this class.
                                </p>
                            </div>
                        </template>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
