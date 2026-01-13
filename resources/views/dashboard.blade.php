{{-- dashboard home page --}}

@extends('layouts.app')

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<div x-data="{
    search: '',
    showAddClassModal: false,
    showRegisterModal: false,
    showEditClassModal: false,
    showEndClassModal: false,
    {{-- Fake Class Data --}}
    classes: [
        {
            id: 1,
            name: 'Mathematics Advanced',
            status: 'Active',
            term: 'Sat - Sun',
            room: 'Room 302',
            teacher: 'Dr. Sarah Jenkins',
            time: '08:00 - 10:00'
        }
    ],
    {{-- Filter Logic --}}
    get filteredClasses() {
        return this.classes.filter(c =>
            c.name.toLowerCase().includes(this.search.toLowerCase()) ||
            c.teacher.toLowerCase().includes(this.search.toLowerCase()) ||
            c.room.toLowerCase().includes(this.search.toLowerCase())
        );
    }
}">

    {{-- stats cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- total class--}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Classes</p>
                <p class="text-2xl font-black text-slate-800" x-text="classes.length.toString().padStart(2, '0')">08</p>
            </div>
        </div>

        {{-- total student --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Students</p>
                <p class="text-2xl font-black text-slate-800">240</p>
            </div>
        </div>

        {{-- male --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-cyan-100 text-cyan-600 rounded-2xl flex items-center justify-center shrink-0 font-black">M</div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Male</p>
                <p class="text-2xl font-black text-slate-800">115</p>
            </div>
        </div>

        {{-- female --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center shrink-0 font-black">F</div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Female</p>
                <p class="text-2xl font-black text-slate-800">125</p>
            </div>
        </div>
    </div>

    {{-- search + add class --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <input
                x-model="search"
                type="text"
                placeholder="Search for a class..."
                class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none bg-white font-medium"
            >
        </div>

        <button @click="showAddClassModal = true" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-blue-200 transition active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Class
        </button>
    </div>

    {{-- if there is no class yet or no search results --}}
    <div x-show="filteredClasses.length === 0" class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100" x-cloak>
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h3 class="text-xl font-black text-slate-800 mb-2">No Classes Found</h3>
        <p class="text-slate-400 font-bold mb-8 text-center max-w-xs">
            <span x-show="search === ''">It looks like you haven't created any classes yet.</span>
            <span x-show="search !== ''">No classes match your search term "<span x-text="search" class="text-blue-600"></span>".</span>
        </p>
    </div>

    {{-- class cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <template x-for="cls in filteredClasses" :key="cls.id">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="p-5 bg-slate-100 border-b border-gray-100 group-hover:bg-blue-200 transition-colors">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="text-xl font-black text-slate-800 leading-tight" x-text="cls.name"></h3>
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-md uppercase" x-text="cls.status"></span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Term</p>
                            <p class="text-sm font-bold text-slate-700" x-text="cls.term"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Room</p>
                            <p class="text-sm font-bold text-slate-700" x-text="cls.room"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Teacher</p>
                            <p class="text-sm font-bold text-slate-700" x-text="cls.teacher"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Time</p>
                            <p class="text-sm font-bold text-slate-700" x-text="cls.time"></p>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-dashed border-gray-200 flex items-center justify-between gap-2">
                        <a href="/student" class="flex-1 flex items-center justify-center gap-1.5 px-2 py-3 bg-blue-50 text-blue-700 rounded-xl font-bold text-[10px] hover:bg-blue-100 transition active:scale-95 group uppercase tracking-tighter whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 shrink-0 transition-transform group-hover:translate-x-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0z" />
                            </svg>
                            <span>Students</span>
                        </a>

                        <a href="/take" class="flex-1 flex items-center justify-center gap-1.5 px-2 py-3 bg-green-500 text-white rounded-xl font-bold text-[10px] hover:bg-green-700 transition active:scale-95 shadow-sm shadow-green-100 uppercase tracking-tighter whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Attendance</span>
                        </a>

                        <div class="relative shrink-0" x-data="{ menuOpen: false }">
                            <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                </svg>
                            </button>

                            <div x-show="menuOpen" x-transition x-cloak class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                                <div class="p-2 text-left">
                                    <button @click="showRegisterModal = true; menuOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                        </svg>
                                        Add Student
                                    </button>

                                    <button @click="showEditClassModal = true; menuOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                        Edit Class
                                    </button>

                                    <div class="h-px bg-gray-100 my-1 mx-2"></div>

                                    <button @click="showEndClassModal = true; menuOpen = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                                        </svg>
                                        End Class
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- import modals --}}
    <x-modal-add-class />
    <x-modal-register-student />
    <x-modal-edit-class/>
    <x-modal-end-class/>

</div>
@endsection
