{{-- take daily attendance --}}

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-4">
            {{-- Back Button --}}
            <a href="javascript:void(0)" onclick="window.location.href = document.referrer;" class="p-3 bg-white border border-gray-100 rounded-2xl text-slate-400 hover:text-blue-600 hover:border-blue-100 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daily Attendance</h1>
                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Mathematics Advanced</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="date" value="{{ date('Y-m-d') }}" class="px-5 py-3 bg-white border-2 border-gray-100 rounded-2xl font-bold text-slate-700 outline-none focus:border-blue-500 transition shadow-sm">
            <button @click="$dispatch('notify', { message: 'Attendance Saved!', type: 'success' })" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-blue-200 transition active:scale-95 flex items-center gap-2 cursor-pointer">
                Save Attendance
            </button>
        </div>
    </div>

    {{-- Quick Tools --}}
    <div class="bg-indigo-50 border border-indigo-100 rounded-4xl p-4 mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3 px-4">
            <div class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
            <p class="text-indigo-900 font-bold text-sm">Marking attendance for all students</p>
        </div>
        <button class="px-6 py-2.5 bg-white text-indigo-600 font-black text-xs uppercase tracking-widest rounded-xl shadow-sm hover:bg-indigo-600 hover:text-white transition-all active:scale-95 cursor-pointer">
            Mark All Present
        </button>
    </div>

    {{-- Attendance Table --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800 border-b border-slate-700">
                    <th class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest">Student Information</th>
                    <th class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center">Status</th>
                    <th class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest">Reason / Note</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr class="hover:bg-slate-50/50 transition" x-data="{ status: 'present' }">
                    <td class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center font-black text-slate-500">
                                JD
                            </div>
                            <div>
                                <p class="font-black text-slate-800 text-lg transition">John Doe</p>
                                <p class="text-xs font-bold text-slate-400 uppercase">ID: 2024-001</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-6">
                        <div class="flex items-center justify-center gap-2">
                            <button @click="status = 'present'" :class="status === 'present' ? 'bg-green-500 text-white shadow-lg shadow-green-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                                Present
                            </button>
                            <button @click="status = 'absent'" :class="status === 'absent' ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                                Absent
                            </button>
                            <button @click="status = 'excused'" :class="status === 'excused' ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100'" class="px-5 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                                Excused
                            </button>
                        </div>
                    </td>
                    <td class="p-6">
                        {{-- Reason --}}
                        <input
                            type="text"
                            placeholder="Add reason..."
                            :disabled="status !== 'excused'"
                            :class="status === 'excused' ? 'bg-amber-50 border-amber-200 text-amber-900 placeholder-amber-300' : 'bg-gray-50 border-gray-100 text-gray-400 cursor-not-allowed'"
                            class="w-full px-4 py-3 border-2 rounded-xl text-sm font-bold transition-all outline-none focus:border-blue-500"
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
