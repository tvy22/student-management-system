{{-- remove student from class modal --}}
<div
x-data="{
    isRemoving: false,
    async removeStudentFromClass() {
        this.isRemoving = true;
        try {
            await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', { credentials: 'include' });
            const xsrfToken = window.getCookie('XSRF-TOKEN');
            const response = await fetch('http://127.0.0.1:8000/api/class/remove-student', {
                method: 'DELETE',
                credentials: 'include',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': xsrfToken,
                },
                body: JSON.stringify({
                    class_id: this.classId,
                    student_id: selectedStudentToRemove.id
                })
            });

            if (response.ok) {
                Swal.fire({
                    title: 'Removed!',
                    text: 'Student is no longer in this class.',
                    icon: 'success',
                    confirmButtonColor: '#f59e0b'
                });
                window.dispatchEvent(new CustomEvent('refresh-student-list'));
                this.showRemoveStudentModal = false;
            } else {
                const error = await response.json();
                alert(error.message || 'Failed to remove student.');
            }
        } catch (error) {
            console.error('Removal error:', error);
        } finally {
            this.isRemoving = false;
        }
    }
}"
x-show="showRemoveStudentModal"
x-cloak
class="fixed inset-0 z-100 overflow-y-auto">

    {{-- Backdrop --}}
    <div x-show="showRemoveStudentModal" x-transition.opacity @click="showRemoveStudentModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showRemoveStudentModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden border border-amber-100">

            {{-- Header: Using Amber/Orange to differentiate from Red --}}
            <div class="p-8 bg-amber-50 border-b border-amber-100 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                        {{-- User Minus Icon instead of Trash --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-amber-900">Unenroll Student</h2>
                        <p class="text-xs font-bold text-amber-700/60 uppercase tracking-tight">Class Management</p>
                    </div>
                </div>
                <button @click="showRemoveStudentModal = false" class="p-2 bg-white text-amber-400 hover:text-amber-600 rounded-xl shadow-sm border border-amber-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-8 text-center">
                <p class="text-slate-600 font-bold mb-2">Remove from current class?</p>
                <div class="inline-block px-4 py-2 bg-slate-50 rounded-xl border border-slate-100 mb-6">
                    <span class="text-slate-800 font-black text-lg" x-text="selectedStudentToRemove.name"></span>
                </div>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    The student will be removed from <span class="text-slate-600 font-bold" x-text="selectedClassName"></span>, but their profile and records in other classes will remain intact.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="p-8 pt-0 flex gap-3">
                <button type="button" @click="showRemoveStudentModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition cursor-pointer">
                    Keep
                </button>
                <div class="flex-2">
                    <button
                        @click="removeStudentFromClass"
                        :disabled="isRemoving"
                        type="button"
                        class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl shadow-lg shadow-amber-200 transition-all transform active:scale-[0.98] cursor-pointer flex justify-center items-center"
                    >
                        <span x-show="!isRemoving">Confirm Removal</span>
                        <div x-show="isRemoving" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
