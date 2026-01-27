<div x-show="showRegisterModal" x-cloak
    class="fixed inset-0 z-100 overflow-y-auto"
    x-data="{
        studentForm: { name: '', email: '', phone: '' },
        errors: {},
        generalError: '',
        isSubmitting: false,
        localClassId: null,
        phoneError: '',
        emailError: '',

        init() {
            window.addEventListener('open-register-modal', (e) => {
                this.errors = {}; // Reset errors on open
                this.generalError = ''; // Reset general error
                this.phoneError = ''; // Reset phone error
                this.emailError = ''; // Reset email error
                this.studentForm = { name: '', email: '', phone: '' }; // Reset form

                // If e.detail.id exists, it's an enrollment. If not, it's a general registration.
                this.localClassId = e.detail ? e.detail.id : null;
            });
        },

        // Validate email format
        isValidEmail(email) {
            if (!email || email.trim() === '') return true; // Allow empty (backend will handle required validation)
            // Email must have: text before @, domain after @, at least one dot in domain, characters after dot
            // Pattern: something@domain.extension
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        // Validate email and set error message
        validateEmail() {
            if (!this.isValidEmail(this.studentForm.email)) {
                this.emailError = 'Please enter a valid email address.';
                return false;
            }
            this.emailError = '';
            return true;
        },

        // Validate phone number format
        isValidPhone(phone) {
            if (!phone || phone.trim() === '') return true; // Allow empty (backend will handle required validation)
            // Allow: digits, optional + at start, spaces, dashes
            const phoneRegex = /^\+?[\d\s\-]+$/;
            return phoneRegex.test(phone);
        },

        // Validate phone and set error message
        validatePhone() {
            if (!this.isValidPhone(this.studentForm.phone)) {
                this.phoneError = 'Please enter a valid phone number.';
                return false;
            }
            this.phoneError = '';
            return true;
        },

        // Handle API response and extract errors
        handleApiError(response, result) {
            // Reset previous errors
            this.errors = {};
            this.generalError = '';

            if (response.status === 422) {
                // Laravel validation errors
                if (result.errors) {
                    this.errors = result.errors;
                }
                // Also check for general message
                if (result.message) {
                    this.generalError = result.message;
                }
                return;
            }

            // Other error responses (400, 401, 403, 404, 500, etc.)
            if (result.message) {
                this.generalError = result.message;
            } else if (result.error) {
                this.generalError = result.error;
            } else {
                this.generalError = 'An unexpected error occurred. Please try again.';
            }
        },

        // Normal registration without class enrollment
        async submitGeneralStudent() {
            // Validate email and phone before submission
            const isEmailValid = this.validateEmail();
            const isPhoneValid = this.validatePhone();
            if (!isEmailValid || !isPhoneValid) {
                return;
            }
            this.isSubmitting = true;
            this.errors = {};
            this.generalError = '';
            try {
                const response = await fetch('http://127.0.0.1:8000/api/student/create', {
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
                    this.handleApiError(response, result);
                }
            } catch (error) {
                console.error('Error:', error);
                this.generalError = 'Network error. Please check your connection and try again.';
            } finally {
                this.isSubmitting = false;
            }
        },

        // Registration + Enrollment
        async submitStudentWithClass() {
            // Validate email and phone before submission
            const isEmailValid = this.validateEmail();
            const isPhoneValid = this.validatePhone();
            if (!isEmailValid || !isPhoneValid) {
                return;
            }
            this.isSubmitting = true;
            this.errors = {};
            this.generalError = '';
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
                    this.handleApiError(response, result);
                }
            } catch (error) {
                console.error('Error:', error);
                this.generalError = 'Network error. Please check your connection and try again.';
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
            this.errors = {};
            this.generalError = '';
            this.phoneError = ''; // Reset phone error
            this.emailError = ''; // Reset email error
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

                {{-- General Error Message --}}
                <template x-if="generalError">
                    <div class="p-4 bg-red-50 border-2 border-red-200 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-red-600 text-sm font-bold" x-text="generalError"></p>
                        </div>
                    </div>
                </template>

                <div class="space-y-5">
                    {{-- Full Name --}}
                    <div>
                        <label class="block text-xs font-black uppercase mb-2 ml-1" :class="errors.name ? 'text-red-500' : 'text-gray-700'">Full Name</label>
                        <input x-model="studentForm.name" type="text"
                            :class="errors.name ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50'"
                            class="w-full px-5 py-4 border-2 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700"
                            placeholder="John Doe">
                        <template x-if="errors.name">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-black uppercase mb-2 ml-1" :class="errors.email || emailError ? 'text-red-500' : 'text-gray-700'">Email Address</label>
                        <input x-model="studentForm.email" type="email"
                            @input="validateEmail()"
                            @blur="validateEmail()"
                            :class="errors.email || emailError ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50'"
                            class="w-full px-5 py-4 border-2 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700"
                            placeholder="john@gmail.com">
                        <template x-if="emailError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="emailError"></p>
                        </template>
                        <template x-if="errors.email && !emailError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-xs font-black uppercase mb-2 ml-1" :class="errors.phone || phoneError ? 'text-red-500' : 'text-gray-700'">Phone Number</label>
                        <input x-model="studentForm.phone" type="text"
                            @input="validatePhone()"
                            @blur="validatePhone()"
                            :class="errors.phone || phoneError ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50'"
                            class="w-full px-5 py-4 border-2 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold text-slate-700"
                            placeholder="012 345 678">
                        <template x-if="phoneError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="phoneError"></p>
                        </template>
                        <template x-if="errors.phone && !phoneError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="errors.phone[0]"></p>
                        </template>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" :disabled="isSubmitting"
                        class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] cursor-pointer flex justify-center items-center disabled:opacity-50">
                        <span x-show="!isSubmitting" x-text="localClassId ? 'Enroll Student' : 'Create Student'"></span>
                        <span x-show="isSubmitting" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
