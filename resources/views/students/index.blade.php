{{-- view student page --}}
@extends('layouts.app')

@section('content')
<div x-data="{
    search: '',
    loading: true,
    showRegisterModal: false,
    showEditStudentModal: false,
    showDeleteStudentModal: false,
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
                this.classInfo = result.class_info; // Data from your controller
                this.students = result.data;       // The students array
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
                <a :href="'/take/' + classId" class="flex-1 md:flex-none bg-emerald-400 text-black font-black py-4 px-8 rounded-2xl shadow-lg hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    Attendance
                </a>

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
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-if="loading">
                        <tr><td colspan="5" class="py-20 text-center"><div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div></td></tr>
                    </template>

                    <template x-for="student in filteredStudents" :key="student.id">
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <span class="font-mono font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg text-xs" x-text="student.id"></span>
                            </td>
                            <td class="px-8 py-5"><span class="font-bold text-slate-600" x-text="student.name"></span></td>
                            <td class="px-8 py-5"><span class="font-bold text-slate-600" x-text="student.email"></span></td>
                            <td class="px-8 py-5"><span class="font-bold text-slate-600" x-text="student.phone"></span></td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
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
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals --}}
    <x-modal-register-student />
    <x-modal-edit-student />
    <x-modal-delete-student />

</div>
@endsection
