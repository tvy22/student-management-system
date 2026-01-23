@extends('layouts.app')

@section('content')
    <div class="max-w-full mx-auto py-8 px-4"
        x-data="{
            search: '',
            loading: true,
            showRegisterModal: false,
            showEditStudentModal: false,
            showDeleteStudentModal: false,
            selectedStudentToDelete: { id: null, name: '' },
            students: [],


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
                    const response = await fetch(`http://127.0.0.1:8000/api/student/all`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const result = await response.json();
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

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Student Directory</h1>
                <p class="text-slate-500 font-bold text-sm">Manage and search for students across all classes</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input
                        x-model="search"
                        type="text"
                        placeholder="Search student name or ID..."
                        class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all w-full md:w-64"
                    >
                </div>

                <button @click="showRegisterModal = true" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    <span>Add Student</span>
                </button>
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
                                        <a href='/attendance' class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
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

        {{-- import register student modal --}}
        <x-modal-register-student/>

        {{-- import edit student modal --}}
        <x-modal-edit-student/>

        {{-- import delete student modal --}}
        <x-modal-delete-student/>

    </div>
@endsection
