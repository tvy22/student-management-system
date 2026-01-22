<div x-show="showRegisterModal" x-cloak
    class="fixed inset-0 z-100 overflow-y-auto"
    x-data="{
        studentForm: { name: '', email: '', phone: '' },
        isSubmitting: false,
        localClassId: null,

        init() {
            window.addEventListener('open-register-modal', (e) => {
                // If e.detail.id exists, it's an enrollment. If not, it's a general registration.
                this.localClassId = e.detail ? e.detail.id : null;
            });
        },

        // Normal registration without class enrollment
        async submitGeneralStudent() {
            this.isSubmitting = true;
            try {
                const response = await fetch('http://127.0.0.1:8000/api/student/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.studentForm)
                });

                const result = await response.json();

                if (response.ok) {
                    this.successFeedback('Student created successfully');
                } else {
                    alert(result.message || 'Error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.isSubmitting = false;
            }
        },

        // Registration + Enrollment
        async submitStudentWithClass() {
            this.isSubmitting = true;
            try {
                const response = await fetch('http://127.0.0.1:8000/api/student', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ...this.studentForm,
                        class_id: this.localClassId // Use the local ID captured in init
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    this.successFeedback('Student registered and enrolled.');
                    await fetchClasses(); // Refresh class counts
                } else {
                    alert(result.message || 'Error occurred');
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.isSubmitting = false;
            }
        },

        // Helper to handle UI cleanup after success
        successFeedback(msg) {
            Swal.fire({
                title: 'Success!',
                text: msg,
                icon: 'success',
                confirmButtonColor: '#2563eb',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
            this.studentForm = { name: '', email: '', phone: '' };
            this.localClassId = null; // Reset
            window.dispatchEvent(new CustomEvent('refresh-student-list'));
            showRegisterModal = false;
        }
    }">

    {{-- Backdrop --}}
    <div x-show="showRegisterModal" x-transition.opacity @click="showRegisterModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showRegisterModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-blue-300 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-800" x-text="localClassId ? 'Enroll Student' : 'Create Student'"></h2>
                    <p class="text-sm font-bold text-blue-900/40 uppercase tracking-tight">Enter details below</p>
                </div>
                <button @click="showRegisterModal = false" class="p-2 bg-white text-gray-400 hover:text-gray-600 rounded-xl shadow-sm border border-gray-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form @submit.prevent="localClassId ? submitStudentWithClass() : submitGeneralStudent()" class="p-8 space-y-6">
                @csrf

                <div class="space-y-5">
                    {{-- Full Name --}}
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Full Name</label>
                        <input x-model="studentForm.name" type="text" name="name" placeholder="John Doe" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700" required>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Email</label>
                        <input x-model="studentForm.email" type="email" name="email" placeholder="john@gmail.com" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700" required>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Phone Number</label>
                        <input x-model="studentForm.phone" type="text" name="phone" placeholder="012 345 678" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700" required>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" :disabled="isSubmitting" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] cursor-pointer flex justify-center items-center">
                        <span x-show="!isSubmitting" x-text="localClassId ? 'Enroll Student' : 'Create Student'"></span>
                        <span x-show="isSubmitting" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
