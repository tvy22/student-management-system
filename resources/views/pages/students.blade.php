@extends('layouts.app')

@section('content')
    <div class="max-w-full mx-auto py-8 px-4"
         x-data="{
            search: '',
            showRegisterModal: false,
            showEditStudentModal: false,
            showDeleteStudentModal: false,
            {{-- Student Data --}}
            students: [
                { id: 'STU-942', name: 'Sarah Tan', gender: 'Female', phone: '+60 12-345 6789', classes: ['Math 10A', 'Physics'], joinDate: 'Jan 12, 2026', avatar: 'Sarah+Tan' }
            ],
            {{-- Filter Logic --}}
            get filteredStudents() {
                return this.students.filter(s =>
                    s.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    s.id.toLowerCase().includes(this.search.toLowerCase())
                );
            }
        }">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Student Directory</h1>
                <p class="text-slate-500 font-bold text-sm">Manage and search for students across all departments</p>
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
                    <span>Register Student</span>
                </button>
            </div>
        </div>

        {{-- Student Table --}}
        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">ID</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Profile</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Full Name</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Gender</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Phone</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Enrolled Classes</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Join Date</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="student in filteredStudents" :key="student.id">
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5">
                                    <span class="text-[11px] font-black text-slate-500 bg-slate-100 px-2 py-1 rounded-lg" x-text="student.id"></span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 border-2 border-white shadow-sm overflow-hidden">
                                        <img :src="`https://ui-avatars.com/api/?name=${student.avatar}&background=DBEAFE&color=2563EB&bold=true`" alt="Avatar">
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800 text-sm" x-text="student.name"></div>
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-600" x-text="student.gender"></td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-600 font-mono" x-text="student.phone"></td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="cls in student.classes">
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-black uppercase" x-text="cls"></span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-500" x-text="student.joinDate"></td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button @click="showEditStudentModal = true" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        <button @click="showDeleteStudentModal = true" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
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
