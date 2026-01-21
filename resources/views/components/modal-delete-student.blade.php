{{-- delete student modal --}}

<div
x-data="{
    isDeleting: false,
    async deleteStudent() {
        this.isDeleting = true;
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/student/${selectedStudentToDelete.id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The student record has been removed.',
                    icon: 'success',
                    confirmButtonColor: '#f43f5e'
                });
                window.dispatchEvent(new CustomEvent('refresh-student-list'));
                this.showDeleteStudentModal = false;
            } else {
                alert('Failed to delete student.');
            }
        } catch (error) {
            console.error('Delete error:', error);
        } finally {
            this.isDeleting = false;
        }
    }
}"
x-show="showDeleteStudentModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    {{-- Backdrop --}}
    <div x-show="showDeleteStudentModal" x-transition.opacity @click="showDeleteStudentModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showDeleteStudentModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-rose-50 border-b border-rose-100 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-rose-900">Delete Student</h2>
                        <p class="text-xs font-bold text-rose-700/60 uppercase tracking-tight">Confirm Removal</p>
                    </div>
                </div>
                <button @click="showDeleteStudentModal = false" class="p-2 bg-white text-rose-400 hover:text-rose-600 rounded-xl shadow-sm border border-rose-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Warning Body --}}
            <div class="p-8 text-center">
                <p class="text-slate-600 font-bold mb-2">Are you sure you want to delete this student?</p>
                <div class="inline-block px-4 py-2 bg-slate-50 rounded-xl border border-slate-100 mb-6">
                    <span class="text-slate-800 font-black text-lg" x-text="selectedStudentToDelete.name"></span>
                </div>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    This action cannot be undone. All attendance history associated with this student will be permanently removed from the system.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="p-8 pt-0 flex gap-3">
                <button type="button" @click="showDeleteStudentModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition cursor-pointer">
                    Cancel
                </button>
                <div class="flex-2">
                    <button
                        @click="deleteStudent"
                        :disabled="isDeleting"
                        type="button"
                        class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white font-black rounded-2xl shadow-lg shadow-rose-200 transition-all transform active:scale-[0.98] cursor-pointer flex justify-center items-center"
                    >
                        <span x-show="!isDeleting">Yes, Delete Student</span>
                        <div x-show="isDeleting" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
