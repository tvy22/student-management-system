@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto py-8 px-4"
    x-data="{
        showAttendanceInfoModal: false,
        showEditAttendanceModal: false,
        editAttendanceData: {},
        selectedRecord: {},
        filterStudent: '',
        filterClass: '',
        filterDate: '',
        filterStatus: '',

        records: [
            { id: 101, student_id: 5, student_name: 'Nalin', class_id: 'A101', date: '2026-01-21', status: 'Present', remark: '' },
            { id: 102, student_id: 2, student_name: 'Jenn', class_id: 'B303', date: '2026-01-21', status: 'Absent', remark: '' },
            { id: 104, student_id: 20, student_name: 'Lila', class_id: 'C111', date: '2026-01-19', status: 'Present', remark: '' }
        ],

        get filteredRecords() {
            return this.records.filter(r => {
                const matchStudent = this.filterStudent === '' || r.student_id.toString() === this.filterStudent;
                const matchClass = this.filterClass === '' || r.class_id === this.filterClass;
                const matchStatus = this.filterStatus === '' || r.status === this.filterStatus;
                const matchDate = this.filterDate === '' || r.date.startsWith(this.filterDate);
                return matchStudent && matchClass && matchDate && matchStatus;
            });
        },

        resetFilters() {
            this.filterStudent = '';
            this.filterClass = '';
            this.filterDate = '';
            this.filterStatus = '';
        },

        updateAttendance() {
            console.log('Saving data...', this.editAttendanceData);
            this.showEditAttendanceModal = false;
        }
    }">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Track Attendance Record</h1>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-800 text-white">
                    <th class="px-8 py-5 text-[11px] font-black uppercase">ID</th>
                    <th class="px-8 py-5 text-[11px] font-black uppercase">Student</th>
                    <th class="px-8 py-5 text-[11px] font-black uppercase">Class ID</th>
                    <th class="px-8 py-5 text-[11px] font-black uppercase">Status</th>
                    <th class="px-8 py-5 text-[11px] font-black uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="record in filteredRecords" :key="record.id">
                    <tr class="hover:bg-blue-50/50">
                        <td class="px-8 py-5 font-mono font-black text-blue-600 text-xs" x-text="'#' + record.id"></td>
                        <td class="px-8 py-5 font-bold text-slate-700" x-text="record.student_name"></td>
                        <td class="px-8 py-5 font-bold text-slate-600" x-text="record.class_id"></td>
                        <td class="px-8 py-5">
                            <span x-text="record.status" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-100"></span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <button @click="editAttendanceData = Object.assign({}, record); showEditAttendanceModal = true"
                                    class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Modals --}}
    <x-modal-edit-attendance />
</div>
@endsection
