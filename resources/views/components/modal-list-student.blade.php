<div x-show="showListModal" x-cloak
    class="fixed inset-0 z-100 overflow-y-auto"
    x-data="{
        search: '',
        students: [],
        loading: false,
        showListModal: false,
        currentClassId: null,
        enrolledIds: [],

        init() {
            // Listen for the signal to open this specific modal
            window.addEventListener('open-list-student-modal', async (e) => {
                this.currentClassId = e.detail.id;
                this.enrolledIds = e.detail.enrolledIds || [];
                console.log('Class ID received in modal:', this.currentClassId);
                this.$data.showListModal = true;
                await this.fetchStudents();
            });
        },

        async fetchStudents() {
            this.loading = true;
            try {
                const response = await fetch('http://127.0.0.1:8000/api/student/all', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const result = await response.json();
                    this.students = result.data;
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.loading = false;
            }
        },

        get filteredStudents() {
            return this.students.filter(s =>
                s.name.toLowerCase().includes(this.search.toLowerCase()) ||
                s.id.toString().includes(this.search.toLowerCase())
            );
        },

        async enrollExistingStudent(studentId) {

            if (this.enrolledIds.includes(studentId)) {
                    Swal.fire({
                        title: 'Already Enrolled',
                        text: 'This student is already a member of this class.',
                        icon: 'info',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    return; // Stop the function here
                }

            if (!this.currentClassId) {
                console.error('Enrollment failed: No Class ID found');
                return;
            }
            try {
                await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', { credentials: 'include' });
                const xsrfToken = window.getCookie('XSRF-TOKEN');
                const response = await fetch(`http://127.0.0.1:8000/api/student/enroll`, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-XSRF-TOKEN': xsrfToken,
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        class_id: this.currentClassId
                    })
                });

                if (response.ok) {
                    Swal.fire({
                        title: 'Enrolled!',
                        text: 'Existing student added to class.',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    });
                    this.$data.showListModal = false;
                    // Trigger refresh on the Class page
                    window.dispatchEvent(new CustomEvent('refresh-class-details'));
                    await fetchStudents();
                }else {
                    alert(result.message || 'Enrollment failed');
                }
            } catch (error) {
                console.error('Enrollment error:', error);
            }
        }
    }">

    {{-- Backdrop --}}
    <div x-show="showListModal" x-transition.opacity @click="showListModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showListModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-slate-800 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-white">Select Existing Student</h2>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tight">Search and add to class</p>
                </div>
                <button @click="showListModal = false" class="p-2 bg-white/10 text-white hover:bg-white/20 rounded-xl transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Search Bar --}}
            <div class="p-6 border-b border-slate-50">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input x-model="search" type="text" placeholder="Search by name or student ID..." class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700">
                </div>
            </div>

            {{-- Table Body --}}
            <div class="max-h-100 overflow-y-auto p-4">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-100">
                        <template x-if="loading">
                            <tr><td class="py-10 text-center"><div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div></td></tr>
                        </template>

                        <template x-for="student in filteredStudents" :key="student.id">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-black text-blue-600 text-xs" x-text="student.name.charAt(0)"></div>
                                        <div>
                                            <p class="font-black text-slate-700 leading-none" x-text="student.name"></p>
                                            <div class="flex items-center gap-3">
                                                <p class="text-[10px] font-bold text-slate-400 mt-1" x-text="'ID: ' + student.id"></p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-1" x-text="'Email: ' + student.email"></p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-1" x-text="'Phone: ' + student.phone"></p>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <template x-if="enrolledIds.includes(student.id)">
                                        <span class="px-4 py-2 bg-slate-300 text-slate-600 rounded-xl font-black text-xs">
                                            Already in Class
                                        </span>
                                    </template>

                                    <template x-if="!enrolledIds.includes(student.id)">
                                        <button @click="enrollExistingStudent(student.id)"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black text-xs transition active:scale-95 cursor-pointer">
                                            Add to Class
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
