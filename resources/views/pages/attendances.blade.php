@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto py-8 px-4"
    x-data="{
        showAttendanceInfoModal: false,
        showEditAttendanceModal: false,
        editAttendanceData: {},
        selectedRecord: {},
        search: '', // New search variable

        records: [
            { id: 101, student_id: 5, student_name: 'Nalin', student_phone: '01234567', student_email: 'nalin@gmail.com', class_id: 'A101', date: '2026-01-21', status: 'Present', remark: 'On time' },
            { id: 102, student_id: 2, student_name: 'Jenn', student_phone: '01234568', student_email: 'jenn@gmail.com', class_id: 'B303', date: '2026-01-21', status: 'Absent', remark: 'Medical leave' },
            { id: 104, student_id: 20, student_name: 'Lila', student_phone: '01234569', student_email: 'lila@gmail.com', class_id: 'C111', date: '2026-01-19', status: 'Present', remark: '-' }
        ],

        // Logic to filter based on name or ID
        get filteredRecords() {
            if (this.search === '') return this.records;
            return this.records.filter(r =>
                r.student_name.toLowerCase().includes(this.search.toLowerCase()) ||
                r.id.toString().includes(this.search)
            );
        },

        updateAttendance() {
            const index = this.records.findIndex(r => r.id === this.editAttendanceData.id);
            if (index !== -1) {
                this.records[index] = { ...this.editAttendanceData };
            }
            this.showEditAttendanceModal = false;
        }
    }">

    {{-- Header & Search Bar --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Track Attendance Record</h1>

        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input
                x-model="search"
                type="text"
                placeholder="Search student or ID..."
                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-slate-100 rounded-2xl focus:border-blue-500 focus:outline-none font-bold text-slate-600 transition-all shadow-sm"
            >
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-800 text-white">
                    <th class="px-8 py-5 text-[11px] font-black uppercase">ID</th>
                    <th class="px-8 py-5 text-[11px] font-black uppercase">Student</th>
                    <th class="px-8 py-5 text-[11px] font-black uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                {{-- Loop through filteredRecords instead of records --}}
                <template x-for="record in filteredRecords" :key="record.id">
                    <tr class="hover:bg-blue-50/50 transition-colors" x-show="true" x-transition>
                        <td class="px-8 py-5 font-mono font-black text-blue-600 text-xs" x-text="record.id"></td>
                        <td class="px-8 py-5 font-bold text-slate-700" x-text="record.student_name"></td>
                        <td class="px-8 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="selectedRecord = record; showAttendanceInfoModal = true"
                                        class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>

                                <button @click="editAttendanceData = Object.assign({}, record); showEditAttendanceModal = true"
                                        class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                {{-- Empty State --}}
                <template x-if="filteredRecords.length === 0">
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-bold text-sm">No students found matching "<span x-text="search"></span>"</p>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <x-modal-attendance-info />
    <x-modal-edit-attendance />
</div>
@endsection
