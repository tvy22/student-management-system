{{-- attendance history per student --}}

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="/student" class="p-3 bg-white border border-gray-100 rounded-2xl text-slate-400 hover:text-blue-600 hover:border-blue-100 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Attendance History</h1>
                <p class="text-slate-400 font-bold text-sm">Student: <span class="text-blue-600">John Doe</span></p>
            </div>
        </div>

        <div class="flex gap-2">
            <span class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-wider border border-slate-200">Mathematics Advanced</span>
        </div>
    </div>

    {{-- stats: rate, total present, absent, excused --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-6 rounded-4xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Attendance Rate</p>
            <p class="text-3xl font-black text-slate-800">92%</p>
        </div>

        <div class="bg-white p-6 rounded-4xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Present</p>
            <p class="text-3xl font-black text-green-500">24</p>
        </div>

        <div class="bg-white p-6 rounded-4xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Absent</p>
            <p class="text-3xl font-black text-red-500">02</p>
        </div>

        <div class="bg-white p-6 rounded-4xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Excused</p>
            <p class="text-3xl font-black text-amber-500">03</p>
        </div>
    </div>

    {{-- recent attendance --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-slate-50/50">
            <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest">Recent Records</h3>
        </div>

        <div class="divide-y divide-gray-50">
            {{-- Status: Present --}}
            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-slate-800 text-lg">October 24, 2023</p>
                        <p class="text-xs font-bold text-green-600 uppercase tracking-tighter">Status: Present</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-400">08:05 AM</span>
            </div>

            {{-- Status: Excused (Permission) --}}
            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h.008v.008H12V7.5zM11.25 12h.008v.008H11.25V12zM12 16.5h.008v.008H12v-.008z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21.75a2.25 2.25 0 0 0 2.25-2.25v-1.5A2.25 2.25 0 0 0 9 15.75h-1.5A2.25 2.25 0 0 0 5.25 18v1.5A2.25 2.25 0 0 0 7.5 21.75H9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-slate-800 text-lg">October 23, 2023</p>
                        <p class="text-xs font-bold text-amber-600 uppercase tracking-tighter">Status: Excused (Medical)</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-400">—</span>
            </div>

            {{-- Status: Absent --}}
            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-black text-slate-800 text-lg">October 22, 2023</p>
                        <p class="text-xs font-bold text-red-600 uppercase tracking-tighter">Status: Absent</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-400">—</span>
            </div>
        </div>
    </div>
</div>
@endsection
