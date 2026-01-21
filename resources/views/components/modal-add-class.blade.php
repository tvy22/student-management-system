{{-- add class modal --}}
<div x-data="{
    formData: {
        course: 'React+Laravel',
        room: 'A101',
        term: 'Mon-Thu',
        class_time: '9:00-10:30am'
    },
    async submitClass() {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/class', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.formData)
            });

            {{-- if (response.ok) {
                this.showAddClassModal = false;
                await this.fetchClasses(); // Refresh the list on the dashboard
            } --}}

            if (response.ok) {
                this.showAddClassModal = false;
                await this.fetchClasses();

                // --- SUCCESS POPUP ---
                Swal.fire({
                    title: 'Class Added Successfully!',
                    icon: 'success',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Ok',
                    customClass: {
                        popup: 'rounded-[3rem]',
                        confirmButton: 'rounded-xl font-bold px-6 py-3'
                    }
                });

                // ---------------------
            } else {
                this.errorMessage = data.message || 'Registration failed.';
            }

        } catch (error) {
            console.error('Submission failed:', error);
        }
    }
}" x-show="showAddClassModal" x-cloak class="fixed inset-0 z-100 overflow-y-auto">

    <div x-show="showAddClassModal" x-transition.opacity @click="showAddClassModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showAddClassModal" x-transition class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">

            {{-- Header --}}
            <div class="p-8 bg-blue-300 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Create New Class</h2>
                    <p class="text-sm font-bold text-white uppercase tracking-tight">Enter class details below</p>
                </div>
                <button @click="showAddClassModal = false" class="p-2 bg-white text-gray-400 hover:text-gray-600 rounded-xl shadow-sm border border-gray-100 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Form --}}
            <form @submit.prevent="submitClass()" class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Course Name</label>
                        <select x-model="formData.course" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                            <option>React+Laravel</option>
                            <option>HTML/CSS/JavaScript</option>
                            <option>MySql</option>
                            <option>C++</option>
                            <option>Python</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Room</label>
                        <select x-model="formData.room" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                            <option>A101</option>
                            <option>B303</option>
                            <option>C120</option>
                            <option>A204</option>
                            <option>B402</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Term</label>
                        <select x-model="formData.term" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                            <option>Sat-Sun</option>
                            <option>Mon-Fri</option>
                            <option>Mon-Thu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-red-400 uppercase mb-2 ml-1">Class Time</label>
                        <select x-model="formData.class_time" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:border-blue-500 focus:bg-white outline-none transition font-bold">
                            <option>9:00-10:30am</option>
                            <option>11:00-12:30am</option>
                            <option>1:00-2:30pm</option>
                            <option>3:00-4:30pm</option>
                            <option>5:00-6:30pm</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98] cursor-pointer">
                    Add New Class
                </button>
            </form>
        </div>
    </div>
</div>
