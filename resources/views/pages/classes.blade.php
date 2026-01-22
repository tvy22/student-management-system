@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4"
    x-data="{
        search: '',
        loading: true,
        showAddClassModal: false,
        showEditClassModal: false,
        showEndClassModal: false,
        classes: [],
        selectedClassId: null,
        selectedClassName: '',

        editFormData: {
            id: null,
            course: '',
            room: '',
            term: '',
            class_time: ''
        },

        async init() {
            await this.fetchClasses();

            // Listen for the edit event dispatched from the table row
            window.addEventListener('open-edit-class', (event) => {
                const cls = event.detail;
                this.editFormData = {
                    id: cls.id,
                    course: cls.name, // Mapping 'name' from table back to 'course' for API
                    room: cls.room,
                    term: cls.term,
                    class_time: cls.time
                };
                this.showEditClassModal = true;
            });

            window.addEventListener('open-delete-class', (event) => {
                const cls = event.detail;
                this.selectedClassId = cls.id;
                this.selectedClassName = cls.name;
                this.showEndClassModal = true;
            });

            // Listen for refresh events (from add/edit modals)
            window.addEventListener('refresh-class-list', async () => {
                await this.fetchClasses();
            });
        },

        async fetchClasses() {
            this.loading = true;
            try {
                const response = await fetch('http://127.0.0.1:8000/api/class', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    // Map the API data to match your table's x-text fields
                    this.classes = result.data.map(cls => ({
                        id: cls.id,
                        name: cls.course,
                        room: cls.room,
                        term: cls.term,
                        time: cls.class_time,
                        students: cls.students_count || cls.students?.length || 0
                    }));
                }
            } catch (error) {
                console.error('Error fetching classes:', error);
            } finally {
                this.loading = false;
            }
        },

        async updateClass() {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/class/${this.editFormData.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        course: this.editFormData.course,
                        room: this.editFormData.room,
                        term: this.editFormData.term,
                        class_time: this.editFormData.class_time
                    })
                });

                if (response.ok) {
                    this.showEditClassModal = false;
                    await this.fetchClasses();

                    // --- SUCCESS POPUP ---
                    Swal.fire({
                        title: 'Class Deleted Successfully!',
                        icon: 'success',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Ok',
                        customClass: {
                            popup: 'rounded-[3rem]',
                            confirmButton: 'rounded-xl font-bold px-6 py-3'
                        }
                    });

                } else {
                    const error = await response.json();
                    alert('Update failed: ' + (error.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error updating class:', error);
            }
        },

        async deleteClass() {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/class/${this.selectedClassId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.showEndClassModal = false;
                    await this.fetchClasses();

                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Class has been deleted successfully.',
                        icon: 'success',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-[3rem]',
                            confirmButton: 'rounded-xl font-bold px-6 py-3'
                        }
                    });
                } else {
                    alert('Delete failed');
                }
            } catch (error) {
                console.error('Error deleting class:', error);
            }
        },

        get filteredClasses() {
            return this.classes.filter(c =>
                c.name.toLowerCase().includes(this.search.toLowerCase()) ||
                c.id.toString().includes(this.search.toLowerCase()) ||
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
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Class ID</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Course</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Room</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Term</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Time</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center">Students</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            {{-- Loading State --}}
                            <template x-if="loading">
                                <tr>
                                    <td colspan="7" class="py-20 text-center">
                                        <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                                        <p class="text-slate-400 font-bold mt-2">Loading classes...</p>
                                    </td>
                                </tr>
                            </template>

                            {{-- Data State --}}
                            <template x-if="!loading">
                                <template x-for="cls in filteredClasses" :key="cls.id">
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-5">
                                            <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-md" x-text="cls.id"></span>
                                        </td>
                                        <td class="px-6 py-5">
                                            {{-- Course name now rendered normally --}}
                                            <div class="font-bold text-slate-800 text-sm" x-text="cls.name"></div>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-bold text-slate-600" x-text="cls.room"></td>
                                        <td class="px-6 py-5">
                                            <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-[10px] font-black uppercase" x-text="cls.term"></span>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-bold text-slate-600" x-text="cls.time"></td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="text-sm font-black text-slate-800" x-text="cls.students"></span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex justify-end gap-1">
                                                {{-- New View Students Button (Eye Icon) --}}
                                                <a :href="'/student/' + cls.id" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>

                                                <button @click="$dispatch('open-edit-class', cls)" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </button>

                                                <button @click="$dispatch('open-delete-class', cls)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
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
