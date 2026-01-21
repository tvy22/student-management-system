{{-- view student page --}}

@extends('layouts.app')

@section('content')
<div x-data="{
    search: '',
    showRegisterModal: false,
    showEditStudentModal: false,
    showDeleteStudentModal: false,
    students: [
        { id: 'STU-9920', name: 'Johnathan Wick', gender: 'Male', phone: '+1 234 567 890', email: 'j.wick@example.com' },
        { id: 'STU-8831', name: 'Amelia Clarke', gender: 'Female', phone: '+1 987 654 321', email: 'a.clarke@example.com' },
        { id: 'STU-7722', name: 'Marcus Aurelius', gender: 'Male', phone: '+1 555 012 345', email: 'm.aurelius@example.com' }
    ],
    get filteredStudents() {
        return this.students.filter(s =>
            s.name.toLowerCase().includes(this.search.toLowerCase()) ||
            s.id.toLowerCase().includes(this.search.toLowerCase())
        )
    }
}">

{{-- header --}}
    <div class="mb-8">
        <a href="/dashboard" class="text-blue-600 font-bold text-sm flex items-center gap-2 mb-4 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Back to Dashboard
        </a>
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Mathematics Advanced</h1>
                <p class="text-slate-400 font-bold mt-1 uppercase text-xs tracking-widest">Room 302 • Sat-Sun</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                {{-- attendance btn --}}
                <a href="/take" class="flex-1 md:flex-none bg-emerald-400 text-black font-black py-4 px-8 rounded-2xl shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.793.425 1.077.263.284.625.463 1.025.463s.762-.179 1.025-.463C16.138 5.372 16.3 4.994 16.3 4.579c0-.231-.035-.454-.1-.664m-5.801 0A48.474 48.474 0 0 1 11.414 3.9c1.132.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 11.142 18.75h-8.392A2.25 2.25 0 0 1 .5 16.5V6.108c0-1.135.845-2.098 1.976-2.192a48.411 48.411 0 0 1 1.123-.08" />
                    </svg>
                    Attendance
                </a>

                {{-- add student btn --}}
                <button @click="showRegisterModal = true" class="flex-1 md:flex-none bg-blue-600 text-white font-black py-4 px-8 rounded-2xl shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Student
                </button>
            </div>
        </div>
    </div>

    {{-- search bar --}}
    <div class="bg-white p-2 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </span>
            <input
                x-model="search"
                type="text"
                placeholder="Search by name or student ID..."
                class="w-full pl-14 pr-6 py-5 rounded-2xl border-none bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition outline-none font-bold text-slate-700"
            >
        </div>
    </div>

    {{-- table student --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800 text-white">
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Student ID</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Full Name</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Email</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Phone Number</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="student in filteredStudents" :key="student.id">
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            {{-- student id --}}
                            <td class="px-8 py-5">
                                <span class="font-mono font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg text-xs" x-text="student.id"></span>
                            </td>

                            {{-- name --}}
                            <td class="px-8 py-5">
                                <span class="font-bold text-slate-600" x-text="student.name"></span>
                            </td>

                            {{-- email --}}
                            <td class="px-8 py-5">
                                <span class="font-bold text-slate-600" x-text="student.email"></span>
                            </td>

                            {{-- phone --}}
                            <td class="px-8 py-5">
                                <span class="font-bold text-slate-600" x-text="student.phone"></span>
                            </td>

                            {{-- action --}}
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- view attendance history --}}
                                    <a href="/attendance" title="Attendance History" class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                    {{-- edit --}}
                                    <button @click="showEditStudentModal = true" title="Edit" class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    {{-- delete --}}
                                    <button @click="showDeleteStudentModal = true" title="Delete" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all cursor-pointer">
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

    {{-- import add student modal --}}
    <x-modal-register-student />

    {{-- import edit student modal --}}
    <x-modal-edit-student/>

    {{-- import delete student modal --}}
    <x-modal-delete-student/>

</div>
@endsection
