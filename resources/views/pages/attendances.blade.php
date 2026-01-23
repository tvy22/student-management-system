@extends('layouts.app')

@section('content')
    <div class="max-w-full mx-auto py-8 px-4"
        x-data="{
            search: '',
            {{-- Filter States --}}
            filterStudent: '',
            filterClass: '',
            filterDate: '',

            {{-- Mock Data for Frontend Visualization --}}
            records: [
                { id: 101, student_id: 5, student_name: 'Nalin', class_id: 'A101', class_name: 'Python', date: '2026-01-21', status: 'Present', remark: 'On time' },
                { id: 102, student_id: 2, student_name: 'Jenn', class_id: 'B303', class_name: 'HTML/CSS', date: '2026-01-21', status: 'Absent', remark: 'Medical leave' },
                { id: 103, student_id: 8, student_name: 'Nath', class_id: 'A101', class_name: 'Python', date: '2026-01-20', status: 'Late', remark: 'Traffic jam' },
                { id: 104, student_id: 5, student_name: 'Nalin', class_id: 'C111', class_name: 'Computer Network', date: '2026-01-19', status: 'Present', remark: '-' }
            ],

            {{-- Frontend Filter Logic --}}
            get filteredRecords() {
                return this.records.filter(r => {
                    const matchStudent = this.filterStudent === '' || r.student_id.toString() === this.filterStudent;
                    const matchClass = this.filterClass === '' || r.class_id === this.filterClass;
                    const matchDate = this.filterDate === '' || r.date === this.filterDate;

                    return matchStudent && matchClass && matchDate;
                });
            },

            resetFilters() {
                this.filterStudent = '';
                this.filterClass = '';
                this.filterDate = '';
            }
        }">

        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Track Attendance Record</h1>
            <p class="text-slate-500 font-bold text-sm">View and filter student attendance history</p>
        </div>

        {{-- Filter Card --}}
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                {{-- Student Selection --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Student</label>
                    <select x-model="filterStudent" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none cursor-pointer">
                        <option value="">All Students</option>
                        <option value="5">Nalin (ID: 5)</option>
                        <option value="2">Jenn (ID: 2)</option>
                        <option value="8">Nath (ID: 8)</option>
                    </select>
                </div>

                {{-- Class Selection --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Class</label>
                    <select x-model="filterClass" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none cursor-pointer">
                        <option value="">All Classes</option>
                        <option value="A101">Python (A101)</option>
                        <option value="B303">HTML/CSS (B303)</option>
                        <option value="C111">Computer Network (C111)</option>
                    </select>
                </div>

                {{-- Date Selection --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 ml-2">Date</label>
                    <input type="date" x-model="filterDate" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                </div>

                {{-- Reset Button --}}
                <div>
                    <button @click="resetFilters()" class="w-full bg-slate-800 text-white py-3 rounded-2xl font-black text-sm hover:bg-slate-900 transition shadow-lg cursor-pointer">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        {{-- Attendance Table --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800 text-white">
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">ID</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Student ID</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Class ID</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Date</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-5 text-[11px] font-black uppercase tracking-[0.2em]">Remark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="record in filteredRecords" :key="record.id">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <span class="font-mono font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg text-xs" x-text="record.id"></span>
                                </td>
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
                                <td class="px-8 py-5 text-sm font-medium text-slate-500 italic" x-text="record.remark"></td>
                            </tr>
                        </template>

                        {{-- Empty State --}}
                        <template x-if="filteredRecords.length === 0">
                            <tr>
                                <td colspan="6" class="py-20 text-center text-slate-400 font-bold">
                                    No records match your filters.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
