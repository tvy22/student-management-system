<div x-show="showEditAttendanceModal" x-cloak class="fixed inset-0 z-9999 overflow-y-auto">
    {{-- Backdrop --}}
    <div x-show="showEditAttendanceModal" x-transition.opacity @click="showEditAttendanceModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="showEditAttendanceModal" x-transition class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden">

            <div class="p-8 bg-amber-50 border-b border-amber-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-amber-900">Edit Attendance</h2>
                    <p class="text-sm font-bold text-amber-700/60" x-text="'Updating ' + (editAttendanceData.student_name || '')"></p>
                </div>
                <button @click="showEditAttendanceModal = false" class="text-amber-400 hover:text-amber-600 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form @submit.prevent="updateAttendance()" class="p-8 space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 ml-1">Change Status</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button"
                            @click="editAttendanceData.status = 'present'"
                            :class="editAttendanceData.status?.toLowerCase() === 'present' ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-50 text-gray-400'"
                            class="py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                            Present
                        </button>

                        <button type="button"
                            @click="editAttendanceData.status = 'absent'"
                            :class="editAttendanceData.status?.toLowerCase() === 'absent' ? 'bg-red-500 text-white shadow-lg' : 'bg-gray-50 text-gray-400'"
                            class="py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                            Absent
                        </button>

                        <button type="button"
                            @click="editAttendanceData.status = 'permission'"
                            :class="editAttendanceData.status?.toLowerCase() === 'permission' ? 'bg-amber-500 text-white shadow-lg' : 'bg-gray-50 text-gray-400'"
                            class="py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                            Permission
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Remark / Reason</label>
                    <textarea x-model="editAttendanceData.remark" rows="3" class="w-full px-5 py-4 border-2 border-gray-100 rounded-4xl bg-gray-50 outline-none focus:bg-white focus:border-amber-500"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showEditAttendanceModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl cursor-pointer">Cancel</button>
                    <button type="submit"
                            :disabled="isLoading"
                            :class="isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-amber-600'"
                            class="flex-2 py-4 bg-amber-500 text-white font-black rounded-2xl shadow-lg cursor-pointer flex items-center justify-center gap-2">
                        <template x-if="isLoading">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isLoading ? 'Updating...' : 'Update Attendance'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
