@extends('layouts.app')

@section('content')
    <div class="max-w-full mx-auto py-8 px-4"
        x-data="{
            showAttendanceInfoModal: false,
            selectedRecord: {},
            search: '',
            filterStudent: '',
            filterClass: '',
            filterDate: '',
            filterStatus: '', {{-- New Status Filter Variable --}}

            records: [
                {
                    id: 101, student_id: 5, student_name: 'Nalin',
                    student_phone: '01234567', student_email: 'nalin@gmail.com',
                    class_id: 'A101', class_name: 'Python', room: 'A101', term: 'Sat-Sun', class_time: '11:00-12:30am',
                    date: '2026-01-21', status: 'Present', remark: 'On time'
                },
                {
                    id: 102, student_id: 2, student_name: 'Jenn',
                    student_phone: '01234568', student_email: 'jenn@gmail.com',
                    class_id: 'B303', class_name: 'HTML/CSS/JavaScript', room: 'B303', term: 'Mon-Fri', class_time: '9:00-10:30am',
                    date: '2026-01-21', status: 'Absent', remark: 'Medical leave'
                },
                {
                    id: 104, student_id: 20, student_name: 'Lila',
                    student_phone: '01234568', student_email: 'lila@gmail.com',
                    class_id: 'C111', class_name: 'Computer Network', room: 'C111', term: 'Sat/Sun', class_time: '6:00-8:00',
                    date: '2026-01-19', status: 'Present', remark: '-'
                }
            ],

            get filteredRecords() {
                return this.records.filter(r => {
                    const matchStudent = this.filterStudent === '' || r.student_id.toString() === this.filterStudent;
                    const matchClass = this.filterClass === '' || r.class_id === this.filterClass;
                    const matchStatus = this.filterStatus === '' || r.status === this.filterStatus;

                    // This is the magic line
                    const matchDate = this.filterDate === '' || r.date.startsWith(this.filterDate);

                    return matchStudent && matchClass && matchDate && matchStatus;
                });
            },

            resetFilters() {
                this.filterStudent = '';
                this.filterClass = '';
                this.filterDate = '';
                this.filterStatus = '';
            }
        }">

        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight text-center md:text-left">Track Attendance Record</h1>
            <p class="text-slate-500 font-bold text-sm text-center md:text-left">View and filter student attendance history</p>
        </div>

        {{-- Filter Card --}}
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end"> {{-- Changed to grid-cols-5 for the extra status filter --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Student</label>
                    <select x-model="filterStudent" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 outline-none cursor-pointer">
                        <option value="">All Students</option>
                        <option value="5">Nalin (ID: 5)</option>
                        <option value="2">Jenn (ID: 2)</option>
                        <option value="20">Lila (ID: 20)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Class</label>
                    <select x-model="filterClass" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 outline-none cursor-pointer">
                        <option value="">All Classes</option>
                        <option value="A101">Python (A101)</option>
                        <option value="B303">HTML/CSS (B303)</option>
                        <option value="C111">Computer Network (C111)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Status</label>
                    <select x-model="filterStatus" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 outline-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Filter by Month</label>
                    <input type="month"
                        x-model="filterDate"
                        class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 outline-none">
                </div>

                <button @click="resetFilters()" class="w-full bg-slate-800 text-white py-4 rounded-2xl font-black text-sm hover:bg-slate-900 transition shadow-lg cursor-pointer">
                    Reset
                </button>
            </div>
        </div>

        {{-- Attendance Table --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800 text-white">
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">ID</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Student</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Class ID</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Date</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="record in filteredRecords" :key="record.id">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-8 py-5 font-mono font-black text-blue-600 text-xs" x-text="'#' + record.id"></td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700" x-text="record.student_name"></span>
                                        <span class="font-mono text-[10px] text-slate-400 font-black" x-text="'ID: ' + record.student_id"></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 font-bold text-slate-600" x-text="record.class_id"></td>
                                <td class="px-8 py-5 font-bold text-slate-600" x-text="record.date"></td>
                                <td class="px-8 py-5">
                                    <span :class="{
                                        'bg-emerald-50 text-emerald-600': record.status === 'Present',
                                        'bg-red-50 text-red-600': record.status === 'Absent',
                                        'bg-amber-50 text-amber-600': record.status === 'Late'
                                    }" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest" x-text="record.status"></span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="$dispatch('open-attendance-modal', record)"
                                                class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        <button class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        <button class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- The Modal Component --}}
    <x-modal-attendance-info/>
@endsection
