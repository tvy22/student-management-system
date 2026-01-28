<div x-data="{
        show: false,
        loading: false,
        studentName: '',
        courseName: '',
        stats: {
            attendance_rate: '0%',
            total_present: 0,
            total_absent: 0,
            total_permission: 0
        },
        records: [],

        async fetchRecords(studentId) {
            this.loading = true;
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/attendence/${studentId}?class_id=${classId}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('school_token')}`,
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    this.records = result.attendance.data;
                }
            } catch (e) {
                console.error('Failed to fetch attendance history', e);
            } finally {
                this.loading = false;
            }
        }
    }"
    x-show="show"
    @open-attendance-history.window="
        show = true;
        studentName = $event.detail.studentName;
        courseName = $event.detail.courseName;
        if($event.detail.stats) {
            stats = $event.detail.stats;
        } else {
            stats = { attendance_rate: '0%', total_present: 0, total_absent: 0, total_permission: 0 };
        }
        fetchRecords($event.detail.studentId);
    "
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;">

    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-md transition-opacity"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-50 w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden border border-white/20"
             @click.away="show = false">

            {{-- Top Header --}}
            <div class="p-8 md:p-10 pb-6">
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center gap-5">
                        <button @click="show = false"
                                class="p-3.5 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm cursor-pointer active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Attendance Report</h1>
                            <p class="text-slate-500 font-bold text-sm mt-1">Viewing history for <span class="text-blue-600 underline decoration-blue-200 underline-offset-4" x-text="studentName"></span></p>
                        </div>
                    </div>

                    <div class="hidden sm:block">
                        <span x-text="courseName" class="px-5 py-2.5 bg-slate-800 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.15em] shadow-lg shadow-slate-200"></span>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-2">
                    <div class="bg-white p-6 rounded-4xl border-b-4 border-blue-500 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Avg Rate</p>
                        <p class="text-3xl font-black text-slate-800" x-text="stats.attendance_rate"></p>
                    </div>

                    <div class="bg-white p-6 rounded-4xl border-b-4 border-emerald-500 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Present</p>
                        <p class="text-3xl font-black text-emerald-500" x-text="stats.total_present"></p>
                    </div>

                    <div class="bg-white p-6 rounded-4xl border-b-4 border-amber-500 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Permit</p>
                        <p class="text-3xl font-black text-amber-500" x-text="stats.total_permission"></p>
                    </div>

                    <div class="bg-white p-6 rounded-4xl border-b-4 border-rose-500 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Absent</p>
                        <p class="text-3xl font-black text-rose-500" x-text="stats.total_absent"></p>
                    </div>
                </div>
            </div>

            {{-- Records List Area --}}
            <div class="bg-white rounded-t-[3.5rem] border-t border-slate-200 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="p-6 px-10 border-b border-slate-100 bg-slate-50/50 backdrop-blur-md sticky top-0 z-10 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 uppercase text-[10px] tracking-[0.2em]">Recent History</h3>
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                </div>

                <div class="max-h-[45vh] overflow-y-auto divide-y divide-slate-50">
                    <template x-if="loading">
                        <div class="p-20 text-center">
                            <div class="relative inline-flex">
                                <div class="w-12 h-12 border-4 border-blue-100 rounded-full"></div>
                                <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 mt-4 uppercase tracking-[0.2em]">Synchronizing Data...</p>
                        </div>
                    </template>

                    <template x-if="!loading && records.length > 0">
                        <div class="relative">
                            <div class="absolute left-14 top-0 bottom-0 w-0.5 bg-slate-100"></div>

                            <template x-for="record in records" :key="record.id">
                                <div class="p-6 px-10 flex items-center justify-between hover:bg-slate-50/80 transition-all group relative">
                                    <div class="flex items-center gap-6 relative z-10">
                                        {{-- Icon Status --}}
                                        <div :class="{
                                            'bg-emerald-500 text-white shadow-emerald-200': record.status === 'present',
                                            'bg-rose-500 text-white shadow-rose-200': record.status === 'absent',
                                            'bg-amber-500 text-white shadow-amber-200': record.status === 'permission'
                                        }" class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg transition-transform group-hover:scale-110">
                                            <template x-if="record.status === 'present'">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                            </template>
                                            <template x-if="record.status === 'absent'">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                            </template>
                                            <template x-if="record.status === 'permission'">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path d="M12 7.5h.008v.008H12V7.5z" /></svg>
                                            </template>
                                        </div>

                                        <div>
                                            <p class="font-black text-slate-800 text-lg leading-tight" x-text="new Date(record.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })"></p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span :class="{
                                                    'text-emerald-600 bg-emerald-50': record.status === 'present',
                                                    'text-rose-600 bg-rose-50': record.status === 'absent',
                                                    'text-amber-600 bg-amber-50': record.status === 'permission'
                                                }" class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md" x-text="record.status"></span>
                                                <span x-show="record.remark" class="text-xs font-medium text-slate-400" x-text="'— ' + record.remark"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest" x-text="record.class.class_time"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!loading && records.length === 0">
                        <div class="p-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor font-light"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-slate-400 font-bold tracking-tight">No historical logs available.</p>
                        </div>
                    </template>
                </div>

                {{-- Footer Decoration --}}
                <div class="p-6 bg-slate-50 border-t border-slate-100 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">End of Records</p>
                </div>
            </div>
        </div>
    </div>
</div>
