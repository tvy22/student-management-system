{{-- dashboard home page --}}

@extends('layouts.app')

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<div x-data="{
    search: '',
    loading: true,
    showAddClassModal: false,
    showRegisterModal: false,
    showEditClassModal: false,
    showEndClassModal: false,
    showTakeAttendanceModal: false,
    selectedClassId: null,
    selectedClassName: '',
    classes: [],
    totalStudents: 0,
    students: [],
    classInfo: {},
    attendanceDate: '{{ date('Y-m-d') }}',

    editFormData: {
        course: '',
        room: '',
        term: '',
        class_time: '',
    },

    async init() {
        await this.fetchClasses();
    },

    // Helper to safely get teacher name from local storage
    get teacherName() {
        try {
            const data = localStorage.getItem('user_data');
            return data ? JSON.parse(data).user.name : 'Teacher';
        } catch (e) { return 'Teacher'; }
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
                this.classes = result.data || [];

                this.totalStudents = this.classes.reduce((sum, cls) => {
                    return sum + (cls.students ? cls.students.length : 0);
                }, 0);
            } else {
                console.error('Failed to fetch classes');
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    },

    async deleteClass() {
        if (!this.selectedClassId) return;

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
                const errorData = await response.json();
                alert('Error: ' + (errorData.message || 'Failed to delete'));
            }
        } catch (error) {
            console.error('Delete Error:', error);
        }
    },

    async updateClass() {
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/class/${this.selectedClassId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.editFormData)
            });

            if (response.ok) {
                this.showEditClassModal = false;
                await this.fetchClasses();

                // --- SUCCESS POPUP ---
                Swal.fire({
                    title: 'Class Updated Successfully!',
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
            console.error('Update error:', error);
        }
    },

    get filteredClasses() {
        return this.classes.filter(c =>
            c.course.toLowerCase().includes(this.search.toLowerCase()) ||
            c.room.toLowerCase().includes(this.search.toLowerCase())
        );
    },

    async fetchStudents(classId) {
        this.selectedClassId = classId;
        {{-- this.loading = true; // Use your existing loading state --}}
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/class/${classId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                this.classInfo = result.class_info;
                this.students = result.data; // This fills the table in your modal
                this.showTakeAttendanceModal = true;
            }
        } catch (error) {
            console.error('Error fetching students:', error);
        } finally {
            this.loading = false;
        }
    },

    async submitAttendance() {
        this.loading = true;

        const rows = document.querySelectorAll('.attendance-row');
        const attendanceData = [];

        rows.forEach(row => {
            const studentId = row.getAttribute('data-student-id');
            const rowData = Alpine.$data(row);
            const formattedDate = this.attendanceDate;

            if (rowData) {
                attendanceData.push({
                    student_id: studentId,
                    class_id: this.selectedClassId,
                    date: formattedDate,
                    status: rowData.status,
                    remark: rowData.note
                });
            }
        });

        if (attendanceData.length === 0) {
            this.loading = false;
            return;
        }

        try {
            const requests = attendanceData.map(data =>
                fetch(`http://127.0.0.1:8000/api/attendence`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
            );

            const responses = await Promise.all(requests);

            // Check if every request was successful
            if (responses.every(r => r.ok)) {
                this.$dispatch('notify', { message: 'Attendance Saved!', type: 'success' });
                this.showTakeAttendanceModal = false;
            } else {
                // Find the first response that failed to extract its error message
                const failedResponse = responses.find(r => !r.ok);
                const errorData = await failedResponse.json();

                let errorMessage = 'Failed to save attendance!';

                // Check if there are specific validation errors (like the date error)
                if (errorData.errors) {
                    const firstKey = Object.keys(errorData.errors)[0];
                    errorMessage = errorData.errors[firstKey][0];
                } else if (errorData.message) {
                    // Fallback to the top-level message if error object is missing
                    errorMessage = errorData.message;
                }

                this.$dispatch('notify', { message: errorMessage, type: 'error' });

                // Note: We don't necessarily close the modal on error
                // so the user can fix the date/input and try again.
            }
        } catch (error) {
            console.error('Error saving attendance:', error);
            this.$dispatch('notify', { message: 'Network error or server is down.', type: 'error' });
        } finally {
            this.loading = false;
        }
    }

}">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Classes</p>
                <p class="text-2xl font-black text-slate-800" x-text="classes.length.toString().padStart(2, '0')">00</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Students</p>
                <p class="text-2xl font-black text-slate-800" x-text="totalStudents">0</p>
            </div>
        </div>
    </div>

    {{-- Search + Add Class --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <input x-model="search" type="text" placeholder="Search for a class..." class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none bg-white font-medium">
        </div>

        <button @click="showAddClassModal = true" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-blue-200 transition active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Class
        </button>
    </div>

    {{-- Loading Spinner --}}
    <div x-show="loading" class="col-span-full py-20 flex flex-col items-center justify-center" x-cloak>
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
        <p class="mt-4 text-slate-400 font-bold">Loading your classes...</p>
    </div>

    {{-- Class Cards Grid + Empty States --}}
    <div x-show="!loading" x-cloak>
        {{-- Success: Show Classes --}}
        <template x-if="filteredClasses.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="cls in filteredClasses" :key="cls.id">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-300 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="p-5 bg-blue-200 border-b border-gray-100 group-hover:bg-blue-300 transition-colors">
                            <h3 class="text-xl font-black text-slate-800 leading-tight" x-text="cls.course"></h3>
                        </div>

                        <div class="p-6 ">
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
                                    <p class="text-sm font-bold text-slate-700" x-text="teacherName"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Time</p>
                                    <p class="text-sm font-bold text-slate-700" x-text="cls.class_time"></p>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-dashed border-gray-200 flex items-center justify-between gap-2">
                                <a :href="'/student/' + cls.id" class="flex-1 flex items-center justify-center gap-1.5 px-2 py-3 bg-blue-50 text-blue-700 rounded-xl font-bold text-[10px] hover:bg-blue-100 transition active:scale-95 group uppercase tracking-tighter whitespace-nowrap">
                                    <span>Students</span>
                                </a>

                                <button @click="fetchStudents(cls.id)" class="flex-1 flex items-center justify-center gap-1.5 px-2 py-3 bg-green-500 text-white rounded-xl font-bold text-[10px] hover:bg-green-700 transition active:scale-95 shadow-sm shadow-green-100 uppercase tracking-tighter whitespace-nowrap cursor-pointer">
                                    <span>Attendance</span>
                                </button>

                                <div class="relative shrink-0" x-data="{ menuOpen: false }">
                                    <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </button>

                                    <div x-show="menuOpen" x-transition x-cloak class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                                        <div class="p-2 text-left">
                                            <button @click="
                                                selectedClassId = cls.id;
                                                editFormData = {
                                                    course: cls.course,
                                                    room: cls.room,
                                                    term: cls.term,
                                                    class_time: cls.class_time,
                                                };
                                                showEditClassModal = true;
                                                menuOpen = false;
                                            " class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition cursor-pointer">
                                                Edit Class
                                            </button>

                                            <button @click="
                                                selectedClassId = cls.id;
                                                selectedClassName = cls.course;
                                                showEndClassModal = true;
                                                menuOpen = false;
                                            " class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">
                                                Delete Class
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Empty State: No results or No data --}}
        <template x-if="filteredClasses.length === 0">
            <div class="py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center px-6">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6">
                    <template x-if="search">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </template>
                    <template x-if="!search">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </template>
                </div>

                <h3 class="text-2xl font-black text-slate-800" x-text="search ? 'No matches found' : 'Welcome, ' + teacherName + '!'"></h3>
                <p class="text-slate-500 font-bold mt-2 max-w-sm mx-auto"
                   x-text="search ? 'We couldn\'t find any class matching \'' + search + '\'. Try checking your spelling or search for a different room.' : 'You haven\'t created any classes yet. Let\'s get your first course set up and ready for students.'">
                </p>

                <div class="mt-8 flex gap-3">
                    <button x-show="search" @click="search = ''" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition cursor-pointer">
                        Clear Search
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Import Modals --}}
    <x-modal-add-class />
    <x-modal-register-student />
    <x-modal-edit-class />
    <x-modal-end-class />
    <x-modal-take-class-attendance />

</div>
@endsection
