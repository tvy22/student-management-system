@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4"
         x-data="{
            search: '',
            showAddClassModal: false,
            showEditClassModal: false,
            showEndClassModal: false,
            {{-- Fake Table Data --}}
            classes: [
                { id: 'CLS-001', name: 'Advanced Mathematics', room: '204', term: 'Mon-Fri', time: '08:30 AM', hours: '200', students: '24' }
            ],
            {{-- Filter Logic --}}
            get filteredClasses() {
                return this.classes.filter(c =>
                    c.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    c.id.toLowerCase().includes(this.search.toLowerCase()) ||
                    c.room.toLowerCase().includes(this.search.toLowerCase())
                );
            }
         }">

        {{-- Header Section: Title + Search + Add Button --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-800">Class Management</h1>
                <p class="text-slate-500 font-bold text-sm">View and manage all your academic sessions</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Search Bar --}}
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input
                        x-model="search"
                        type="text"
                        placeholder="Search classes..."
                        class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all w-full md:w-64"
                    >
                </div>

                {{-- Add Class Button --}}
                <button @click="showAddClassModal = true" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100 active:scale-95 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Class</span>
                </button>
            </div>
        </div>

        {{-- Main Table Section --}}
        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        {{-- Dark Header --}}
                        <thead class="bg-slate-800">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">ID</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Class Name</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Room</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Term</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Time</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Total Hours</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center">Students</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="cls in filteredClasses" :key="cls.id">
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-5">
                                        <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-md" x-text="cls.id"></span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-800 text-sm" x-text="cls.name"></div>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-600" x-text="cls.room"></td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-[10px] font-black uppercase" x-text="cls.term"></span>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-600" x-text="cls.time"></td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-1.5 text-sm font-bold text-slate-500" x-text="cls.hours"></div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="text-sm font-black text-slate-800" x-text="cls.students"></span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex justify-end gap-1">
                                            <button @click="showEditClassModal = true" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            <button @click="showEndClassModal = true" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">
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

        {{-- import modals --}}
        <x-modal-add-class/>
        <x-modal-edit-class/>
        <x-modal-end-class/>

    </div>
@endsection
