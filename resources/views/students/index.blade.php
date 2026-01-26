{{-- view student page --}}
@extends('layouts.app')

@section('content')
<div x-data="{
    search: '',
    loading: true,
    showRegisterModal: false,
    showEditStudentModal: false,
    showDeleteStudentModal: false,
    showTakeAttendanceModal: false,
    attendanceDate: '{{ date('Y-m-d') }}',
    selectedStudentToDelete: { id: null, name: '' },
    selectedClassId: null,
    classInfo: { course_name: 'Loading...', room: '', term: '' },
    students: [],

    // Get the ID from the URL (the URL is /student/{id})
    classId: window.location.pathname.split('/').pop(),

    async init() {
        await this.fetchStudents();

        // Listen for the refresh signal
        window.addEventListener('refresh-student-list', async () => {
            await this.fetchStudents();
        });

        // Listen for the open modal signal
        window.addEventListener('open-register-modal', (e) => {
            this.selectedClassId = e.detail.id;
            this.showRegisterModal = true;
        });

        window.addEventListener('close-register-modal', () => {
            this.showRegisterModal = false;
        });

        // Listener for the Open Edit Modal signal
        window.addEventListener('open-edit-student', (e) => {
            this.showEditStudentModal = true;
        });

        // Listener for the Close Edit Modal signal
        window.addEventListener('close-edit-student', () => {
            this.showEditStudentModal = false;
        });

        window.addEventListener('open-delete-student', (e) => {
            const studentId = e.detail;
            const student = this.students.find(s => s.id === studentId);
            if (student) {
                this.selectedStudentToDelete = { id: student.id, name: student.name };
                this.showDeleteStudentModal = true;
            }
        });

        window.addEventListener('close-delete-student', () => {
            this.showDeleteStudentModal = false;
        });

    },

    async fetchStudents() {
        this.loading = true;
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/class/${this.classId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                this.classInfo = result.class_info;

                // Initialize students with null stats so the UI has a loading state
                this.students = result.data.map(s => ({ ...s, stats: null }));

                // Fetch stats for each student individually
                this.students.forEach(async (student, index) => {
                    try {
                        const statsRes = await fetch(`http://127.0.0.1:8000/api/attendence/Stats?student_id=${student.id}&class_id=${this.classId}`, {
                            method: 'GET',
                            headers: {
                                'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                                'Accept': 'application/json'
                            }
                        });
                        if (statsRes.ok) {
                            const statsData = await statsRes.json();
                            // Update the specific student object with real data
                            this.students[index].stats = statsData.attendance;
                        }
                    } catch (e) {
                        console.error(`Error fetching stats for student ${student.id}:`, e);
                    }
                });
            }
        } catch (error) {
            console.error('Error fetching students:', error);
        } finally {
            this.loading = false;
        }
    },

    get filteredStudents() {
        return this.students.filter(s =>
            s.name.toLowerCase().includes(this.search.toLowerCase()) ||
            s.id.toString().includes(this.search.toLowerCase())
        )
    },

    async submitAttendance() {
        this.loading = true;

        const rows = document.querySelectorAll('.attendance-row');
        const attendanceData = [];

        rows.forEach(row => {
            const studentId = row.getAttribute('data-student-id');

            // Use the official Alpine utility to get data from the row
            const rowData = Alpine.$data(row);

            {{-- const formattedDate = new Date(this.attendanceDate).toISOString().split('T')[0]; --}}
            const formattedDate = this.attendanceDate;

            if (rowData) {
                attendanceData.push({
                    student_id: studentId,
                    class_id: this.classId,
                    date: formattedDate,
                    status: rowData.status,
                    remark: rowData.note
                });
            }
        });

        if (attendanceData.length === 0) {
            this.loading = false;
            return;
        }

        try {
            const requests = attendanceData.map(data =>
                fetch(`http://127.0.0.1:8000/api/attendence`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
            );

            const responses = await Promise.all(requests);

            if (responses.every(r => r.ok)) {
                this.$dispatch('notify', { message: 'Attendance Saved!', type: 'success' });
                this.showTakeAttendanceModal = false;
                window.dispatchEvent(new CustomEvent('refresh-student-list'));
            } else {
                this.$dispatch('notify', { message: 'Failed to save attendance!', type: 'error' });
                this.showTakeAttendanceModal = false;
            }
        } catch (error) {
            console.error('Error saving attendance:', error);
        } finally {
            this.loading = false;
        }
    }

}">

    {{-- Header: Dynamic Titles --}}
    <div class="mb-8">
        <a href="javascript:void(0)"
        @click="window.history.back()"
        class="text-blue-600 font-bold text-sm flex items-center gap-2 mb-4 hover:text-blue-800 transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </a>
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight" x-text="classInfo.course_name"></h1>
                <p class="text-slate-400 font-bold mt-1 uppercase text-xs tracking-widest">
                    Room <span x-text="classInfo.room"></span> • <span x-text="classInfo.total_students"></span> Students
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                {{-- Attendance (Pass the class ID) --}}
                <button @click="showTakeAttendanceModal= true" class="flex-1 md:flex-none bg-emerald-400 text-black font-black py-4 px-8 rounded-2xl shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    Attendance
                </button>

                {{-- Add New Student (Now passes classId to the modal) --}}
                <button
                    @click="
                    selectedClassId = classInfo.id;
                    console.log('Class ID: ', selectedClassId);
                    window.dispatchEvent(new CustomEvent('open-register-modal', { detail: { id: selectedClassId } }))"
                    class="flex-1 md:flex-none bg-blue-600 text-white font-black py-4 px-8 rounded-2xl shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    Enroll New Student
                </button>

                {{-- Add Existing Student to Class --}}
                <button
                    @click="$dispatch('open-list-student-modal', { id: classId })"
                    class="flex-1 md:flex-none bg-sky-400 text-white font-black py-4 px-8 rounded-2xl shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    Enroll Existing Student
                </button>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="bg-white p-2 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </span>
            <input x-model="search" type="text" placeholder="Search by name or student ID..." class="w-full pl-14 pr-6 py-5 rounded-2xl border-none bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition outline-none font-bold text-slate-700">
        </div>
    </div>

    {{-- Student Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800 text-white">
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">ID</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Full Name</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Email</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Phone</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] bg-slate-700 shadow-inner">Attendance Insight</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- 1. Loading State --}}
                    <template x-if="loading">
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                                <p class="text-slate-400 font-bold mt-2">Fetching student records...</p>
                            </td>
                        </tr>
                    </template>

                    {{-- 2. Data State --}}
                    <template x-if="!loading && filteredStudents.length > 0">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <span class="font-mono font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg text-xs" x-text="student.id"></span>
                                </td>
                                <td class="px-8 py-5"><span class="font-bold text-slate-600" x-text="student.name"></span></td>
                                <td class="px-8 py-5"><span class="font-bold text-slate-600" x-text="student.email"></span></td>
                                <td class="px-8 py-5"><span class="font-bold text-slate-600" x-text="student.phone"></span></td>

                                <td class="px-8 py-5 bg-slate-50/50 border-x border-slate-100">
                                    <template x-if="!student.stats">
                                        <div class="flex items-center justify-center py-4">
                                            <div class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                        </div>
                                    </template>

                                    <template x-if="student.stats">
                                        <div class="flex flex-col gap-2 w-48">
                                            <div class="flex justify-between items-end">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Attendance Rate</span>
                                                <span class="text-xs font-black text-blue-600" x-text="student.stats.attendance_rate"></span>
                                            </div>

                                            <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden flex shadow-inner">
                                                <div class="bg-emerald-500 h-full transition-all duration-700"
                                                    :style="`width: ${(student.stats.total_present / student.stats.total_records) * 100}%`"
                                                    x-show="student.stats.total_records > 0"></div>
                                                <div class="bg-amber-400 h-full transition-all duration-700"
                                                    :style="`width: ${(student.stats.total_permission / student.stats.total_records) * 100}%`"
                                                    x-show="student.stats.total_records > 0"></div>
                                                <div class="bg-rose-500 h-full transition-all duration-700"
                                                    :style="`width: ${(student.stats.total_absent / student.stats.total_records) * 100}%`"
                                                    x-show="student.stats.total_records > 0"></div>
                                            </div>

                                            <div class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-tight">
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span class="text-slate-500">P: <span x-text="student.stats.total_present"></span></span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                    <span class="text-slate-500">L: <span x-text="student.stats.total_permission"></span></span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    <span class="text-slate-500">A: <span x-text="student.stats.total_absent"></span></span>
                                                </div>
                                                <div class="ml-auto text-slate-400 font-black">Total: <span x-text="student.stats.total_records"></span></div>
                                            </div>
                                        </div>
                                    </template>
                                </td>

                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="$dispatch('open-attendance-history', {
                                                studentId: student.id,
                                                studentName: student.name,
                                                courseName: classInfo.course_name,
                                                stats: student.stats
                                            })"
                                                class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        <button @click="$dispatch('open-edit-student', student.id)" class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        <button @click="$dispatch('open-delete-student', student.id)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>

                    {{-- 3. Empty State --}}
                    <template x-if="!loading && filteredStudents.length === 0">
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-blue-50 p-6 rounded-[2.5rem] mb-4 text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.998 5.998 0 00-12 0m12 0c0-1.657-1.343-3-3-3m-9 3c0-1.657 1.343-3 3-3m9-3a3 3 0 11-6 0 3 3 0 016 0zm-9-3a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800" x-text="search ? 'No match found' : 'No students enrolled'"></h3>
                                    <p class="text-slate-400 font-bold text-sm mt-1" x-text="search ? 'Try searching for a different name or ID.' : 'This class doesn\'t have any students yet. Enroll a student to get started.'"></p>


                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals --}}
    <x-modal-register-student />
    <x-modal-edit-student />
    <x-modal-delete-student />
    <x-modal-list-student />
    <x-modal-take-class-attendance />
    <x-modal-attendance-history/>

</div>
@endsection
