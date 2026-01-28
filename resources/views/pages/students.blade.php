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

                {{-- <button @click="showRegisterModal = true" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    <span>Add Student</span>
                </button> --}}
            </div>
        </div>

        {{-- Student Table --}}
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-175">
                    <thead>
                        <tr class="bg-linear-to-r from-slate-700 to-slate-800">
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200 first:rounded-tl-xl">ID</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200">Full Name</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200">Email</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200">Phone</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200 text-center last:rounded-tr-xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- 1. Loading State --}}
                        <template x-if="loading">
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full mb-3"></div>
                                    <p class="text-slate-400 font-semibold">Loading directory...</p>
                                </td>
                            </tr>
                        </template>

                        {{-- 2. Data State --}}
                        <template x-if="!loading && filteredStudents.length > 0">
                            <template x-for="(student, index) in filteredStudents" :key="student.id">
                                <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50'" class="hover:bg-blue-50/70 transition-all duration-200 border-l-4 border-l-transparent hover:border-l-blue-500 group">
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md text-xs" x-text="student.id"></span>
                                    </td>
                                    <td class="px-6 py-4"><span class="font-semibold text-slate-700" x-text="student.name"></span></td>
                                    <td class="px-6 py-4"><span class="font-medium text-slate-500 text-sm" x-text="student.email"></span></td>
                                    <td class="px-6 py-4"><span class="font-medium text-slate-500 text-sm" x-text="student.phone"></span></td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button @click="$dispatch('open-edit-student', student.id)" class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-100 rounded-lg transition-all duration-200 cursor-pointer hover:shadow-md hover:scale-105">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            <button @click="$dispatch('open-delete-student', student.id)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-100 rounded-lg transition-all duration-200 cursor-pointer hover:shadow-md hover:scale-105">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </template>

                        {{-- 3. Empty State (No Data or No Search Results) --}}
                        <template x-if="!loading && filteredStudents.length === 0">
                            <tr>
                                <td colspan="5" class="py-24 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        {{-- Icon Container --}}
                                        <div class="bg-slate-100 p-5 rounded-2xl mb-5 text-slate-300">
                                            <template x-if="search">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                </svg>
                                            </template>
                                            <template x-if="!search">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                </svg>
                                            </template>
                                        </div>

                                        {{-- Text Content --}}
                                        <h3 class="text-lg font-bold text-slate-800" x-text="search ? 'No matches found' : 'No students registered'"></h3>
                                        <p class="text-slate-400 font-medium text-sm mt-2 leading-relaxed"
                                        x-text="search ? 'We couldn\'t find any student matching \'' + search + '\'. Try a different ID or name.' : 'Your directory is currently empty. Start by adding a new student.'">
                                        </p>

                                        {{-- Action Button (Only show if not searching) --}}
                                        <div class="mt-6" x-show="!search">
                                            <button @click="showRegisterModal = true" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                Add Your First Student
                                            </button>
                                        </div>
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
