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
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-800 text-white">
                    <th class="px-6 py-5 text-[11px] font-black uppercase">ID</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase">Student</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase">Class</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase text-center">Date</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase text-center">Status</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase">Remark</th>
                    <th class="px-6 py-5 text-[11px] font-black uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="record in filteredRecords" :key="record.id">
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-6 py-5 font-mono font-black text-blue-600 text-xs" x-text="record.id"></td>
                        <td class="px-6 py-5 font-bold text-slate-700" x-text="record.student_name"></td>
                        <td class="px-6 py-5 font-bold text-slate-500 text-sm" x-text="record.class_id"></td>
                        <td class="px-6 py-5 text-center font-bold text-slate-500 text-sm" x-text="record.date"></td>
                        <td class="px-6 py-5 text-center">
                            <span :class="{
                                'bg-emerald-100 text-emerald-600': record.status === 'present',
                                'bg-rose-100 text-rose-600': record.status === 'absent',
                                'bg-amber-100 text-amber-600': record.status === 'permission' || record.status === 'late'
                            }" class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider" x-text="record.status"></span>
                        </td>
                        <td class="px-6 py-5 text-slate-500 text-sm italic" x-text="record.remark"></td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="selectedRecord = record; showAttendanceInfoModal = true"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                                <button @click="editAttendanceData = { ...record, status: record.status.toLowerCase() }; showEditAttendanceModal = true"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Loading State --}}
                <template x-if="isLoading">
                    <tr><td colspan="7" class="py-20 text-center font-bold text-slate-400">Fetching records...</td></tr>
                </template>

                {{-- Empty State --}}
                <template x-if="!isLoading && filteredRecords.length === 0">
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-bold text-sm">No attendance records found.</p>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <x-modal-attendance-info />
    <x-modal-edit-attendance />
</div>
@endsection
