@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto py-8 px-4"
    x-data="{
        showAttendanceInfoModal: false,
        showEditAttendanceModal: false,
        editAttendanceData: {},
        selectedRecord: {},
        search: '',
        records: [], // Start with empty array
        isLoading: true,

        {{-- Fetch data from Controller --}}
        async init() {
            this.isLoading = true;
            try {
                const response = await fetch('http://127.0.0.1:8000/api/attendence/all', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await response.json();
                    // Map the data using the structure seen in your Postman
                    if (result.status === 200 && Array.isArray(result.data)) {
                        this.records = result.data.map(item => ({
                            id: item.id,
                            student_id: item.student_id,
                            student_name: item.student ? item.student.name : 'Unknown',
                            student_phone: item.student ? item.student.phone : 'N/A',
                            student_email: item.student ? item.student.email : 'N/A',

                            // NEW: Class Details for the Modal
                            class_id: item.class_id,
                            course: item.class ? item.class.course : 'N/A', // Adjust 'course_name' to match your DB column
                            room: item.class ? item.class.room : 'TBD',
                            term: item.class ? item.class.term : 'N/A',
                            class_time: item.class ? item.class.class_time : 'N/A',

                            date: item.date,
                            status: (item.status || 'present').toLowerCase(),
                            remark: item.remark || '-'
                        }));
                    }
                } else if (response.status === 401) {
                    console.error('Unauthorized: Check if school_token is valid in localStorage.');
                } else {
                    console.error('Failed to fetch attendance records. Status:', response.status);
                }
            } catch (error) {
                console.error('Error fetching attendance:', error);
            } finally {
                this.isLoading = false;
            }
        },

        get filteredRecords() {
            if (this.search === '') return this.records;
            return this.records.filter(r =>
                r.student_name.toLowerCase().includes(this.search.toLowerCase()) ||
                r.id.toString().includes(this.search) ||
                r.class_id.toString().includes(this.search)
            );
        },

        async updateAttendance() {
            // Basic validation to ensure we have an ID
            if (!this.editAttendanceData.id) return;

            this.isLoading = true; // Use global loading or a local one

            try {
                const response = await fetch(`http://127.0.0.1:8000/api/attendence/${this.editAttendanceData.id}`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: this.editAttendanceData.status.toLowerCase(), // API expects lowercase
                        remark: this.editAttendanceData.remark
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    // Find the record in our local array and update it
                    const index = this.records.findIndex(r => r.id === this.editAttendanceData.id);
                    if (index !== -1) {
                        // Merge updated data from server back into our local state
                        this.records[index] = {
                            ...this.records[index],
                            status: result.data.status,
                            remark: result.data.remark
                        };
                    }

                    this.$dispatch('notify', { message: 'Attendance updated successfully!', type: 'success' });
                    this.showEditAttendanceModal = false;
                } else {
                    // Error handling like we did before
                    let errorMsg = result.message || 'Failed to update';
                    if (result.errors) {
                        errorMsg = result.errors[Object.keys(result.errors)[0]][0];
                    }
                    this.$dispatch('notify', { message: errorMsg, type: 'error' });
                }
            } catch (error) {
                console.error('Update error:', error);
                this.$dispatch('notify', { message: 'Network error occurred', type: 'error' });
            } finally {
                this.isLoading = false;
            }
        }
    }">

    {{-- Header & Search Bar --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Track Attendance Record</h1>

        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input
                x-model="search"
                type="text"
                placeholder="Search student, ID, or class..."
                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-slate-100 rounded-2xl focus:border-blue-500 focus:outline-none font-bold text-slate-600 transition-all shadow-sm"
            >
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-200">
                <thead>
                    <tr class="bg-linear-to-r from-slate-700 to-slate-800">
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200 first:rounded-tl-xl">ID</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200">Student</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200">Class</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200 text-center">Date</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200 text-center">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200">Remark</th>
                        <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-200 text-center last:rounded-tr-xl">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(record, index) in filteredRecords" :key="record.id">
                        <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50'" class="hover:bg-blue-50/70 transition-all duration-200 border-l-4 border-l-transparent hover:border-l-blue-500">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md text-xs" x-text="record.id"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-700" x-text="record.student_name"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-500 text-sm" x-text="record.class_id"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-slate-500 text-sm" x-text="record.date"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span :class="{
                                    'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200': record.status === 'present',
                                    'bg-rose-100 text-rose-700 ring-1 ring-rose-200': record.status === 'absent',
                                    'bg-amber-100 text-amber-700 ring-1 ring-amber-200': record.status === 'permission' || record.status === 'late'
                                }" class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm" x-text="record.status"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-500 text-sm italic" x-text="record.remark"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="selectedRecord = record; showAttendanceInfoModal = true"
                                            class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-all duration-200 cursor-pointer hover:shadow-md hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    {{-- <button @click="editAttendanceData = { ...record, status: record.status.toLowerCase() }; showEditAttendanceModal = true"
                                            class="p-2.5 text-slate-400 hover:text-amber-600 hover:bg-amber-100 rounded-lg transition-all duration-200 cursor-pointer hover:shadow-md hover:scale-105">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Loading State --}}
                    <template x-if="isLoading">
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full mb-3"></div>
                                <p class="font-semibold text-slate-400">Fetching records...</p>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty State --}}
                    <template x-if="!isLoading && filteredRecords.length === 0">
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-slate-100 p-5 rounded-2xl mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-semibold text-sm">No attendance records found.</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <x-modal-attendance-info />
    <x-modal-edit-attendance />
</div>
@endsection
