{{-- edit student modal --}}
<div
x-data="{
    editForm: { id: null, name: '', email: '', phone: '' },
    errors: {},
    generalError: '',
    isUpdating: false,
    phoneError: '',
    emailError: '',

    init() {
        // When the parent triggers the edit, find the student data from the list
        window.addEventListener('open-edit-student', (e) => {
            const studentId = e.detail;
            const student = this.$data.students.find(s => s.id === studentId);

            // Reset errors on open
            this.errors = {};
            this.generalError = '';
            this.phoneError = '';
            this.emailError = '';

            if (student) {
                this.editForm = {
                    id: student.id,
                    name: student.name,
                    email: student.email,
                    phone: student.phone
                };
            }
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
        if (!this.isValidEmail(this.editForm.email)) {
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
        if (!this.isValidPhone(this.editForm.phone)) {
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

    async updateStudent() {
        // Validate email and phone before submission
        const isEmailValid = this.validateEmail();
        const isPhoneValid = this.validatePhone();
        if (!isEmailValid || !isPhoneValid) {
            return;
        }

        this.isUpdating = true;
        this.errors = {};
        this.generalError = '';

        try {
            await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', { credentials: 'include' });
            const xsrfToken = window.getCookie('XSRF-TOKEN');
            const response = await fetch(`http://127.0.0.1:8000/api/student/${this.editForm.id}`, {
                method: 'PATCH',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': xsrfToken,
                },
                body: JSON.stringify({
                    name: this.editForm.name,
                    email: this.editForm.email,
                    phone: this.editForm.phone
                })
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    title: 'Updated!',
                    text: 'Student information has been saved.',
                    icon: 'success',
                    confirmButtonColor: '#f59e0b',
                    customClass: { popup: 'rounded-[2.5rem]' }
                });

                // Reset errors on success
                this.errors = {};
                this.generalError = '';
                this.phoneError = '';
                this.emailError = '';

                window.dispatchEvent(new CustomEvent('refresh-student-list'));
                window.dispatchEvent(new CustomEvent('close-edit-student'));
            } else {
                this.handleApiError(response, result);
            }
        } catch (error) {
            console.error('Error updating:', error);
            this.generalError = 'Network error. Please check your connection and try again.';
        } finally {
            this.isUpdating = false;
        }
    }
}"
x-show="showEditStudentModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">
    <div x-show="showEditStudentModal" x-transition.opacity @click="showEditStudentModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showEditStudentModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-amber-50 border-b border-amber-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-amber-900">Edit Student</h2>
                    <p class="text-sm font-bold text-amber-700/60 uppercase tracking-tight">Update Student Information</p>
                </div>
                <button @click="showEditStudentModal = false" class="p-2 bg-white text-amber-400 hover:text-amber-600 rounded-xl shadow-sm border border-amber-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form @submit.prevent="updateStudent" action="#" class="p-8 space-y-6">
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
                        <label class="block text-[10px] font-black uppercase mb-2 ml-1" :class="errors.name ? 'text-red-500' : 'text-gray-400'">Full Name</label>
                        <input x-model="editForm.name" type="text" name="name"
                            :class="errors.name ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50'"
                            class="w-full px-5 py-4 border-2 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        <template x-if="errors.name">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2 ml-1" :class="errors.email || emailError ? 'text-red-500' : 'text-gray-400'">Email</label>
                        <input x-model="editForm.email" type="text" name="email"
                            @input="validateEmail()"
                            @blur="validateEmail()"
                            :class="errors.email || emailError ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50'"
                            class="w-full px-5 py-4 border-2 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        <template x-if="emailError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="emailError"></p>
                        </template>
                        <template x-if="errors.email && !emailError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase mb-2 ml-1" :class="errors.phone || phoneError ? 'text-red-500' : 'text-gray-400'">Phone Number</label>
                        <input x-model="editForm.phone" type="text" name="phone"
                            @input="validatePhone()"
                            @blur="validatePhone()"
                            :class="errors.phone || phoneError ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50'"
                            class="w-full px-5 py-4 border-2 rounded-2xl focus:border-amber-500 focus:bg-white outline-none transition font-bold text-slate-700">
                        <template x-if="phoneError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="phoneError"></p>
                        </template>
                        <template x-if="errors.phone && !phoneError">
                            <p class="text-red-500 text-[10px] font-bold mt-2 ml-1 uppercase" x-text="errors.phone[0]"></p>
                        </template>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-2 flex gap-3">
                    <button type="button" @click="showEditStudentModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" :disabled="isUpdating"
                        class="flex-2 py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl shadow-lg shadow-amber-200 transition-all transform active:scale-[0.98] cursor-pointer flex justify-center items-center disabled:opacity-50">
                        <span x-show="!isUpdating">Update Student</span>
                        <span x-show="isUpdating" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
