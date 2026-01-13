@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4">
        {{-- Header Section --}}
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-slate-800 mb-4">About the System</h1>
            <p class="text-slate-500 font-medium max-w-3xl mx-auto">
                An easy-to-use platform built to help teachers manage classes, track students, and save time every day.
            </p>
        </div>

        {{-- Description Section --}}
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100 mb-8">
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="flex-1">
                    <div class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-xs font-black uppercase tracking-widest mb-4">
                        Our Purpose
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 mb-4">Why we built this platform</h2>
                    <p class="text-slate-600 leading-relaxed font-medium">
                        In a fast-paced learning environment, teachers often lose valuable time to manual attendance and student tracking. Our system was built to bridge that gap.
                        <br><br>
                        By providing a centralized dashboard for class management, we enable teachers to monitor student presence, manage class schedules, and maintain accurate records with just a few clicks. Our goal is to digitize the classroom experience to make it more efficient, transparent, and organized.
                    </p>
                </div>
                <div class="w-full md:w-1/3 bg-slate-50 rounded-3xl p-8 flex items-center justify-center">
                    {{-- Simple Placeholder for an Icon or Illustration --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-32 h-32 text-blue-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- How It Works Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-4xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <span class="absolute -right-4 -top-4 text-8xl font-black text-slate-50 group-hover:text-blue-50 transition-colors">1</span>
                <div class="relative">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Create Classes</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">Set up your subjects, assign teachers, and define schedules to organize your teaching day.</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-4xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <span class="absolute -right-4 -top-4 text-8xl font-black text-slate-50 group-hover:text-green-50 transition-colors">2</span>
                <div class="relative">
                    <div class="w-12 h-12 bg-green-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Enroll Students</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">Easily add students to specific classes and manage their profiles in one central location.</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-4xl border border-slate-100 shadow-sm relative overflow-hidden group">
                <span class="absolute -right-4 -top-4 text-8xl font-black text-slate-50 group-hover:text-orange-50 transition-colors">3</span>
                <div class="relative">
                    <div class="w-12 h-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-orange-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Track Attendance</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">Use the "Take Attendance" button to mark presence daily and keep a history of student participation.</p>
                </div>
            </div>
        </div>
    </div>

@endsection

