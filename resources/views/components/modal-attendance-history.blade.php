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
                    // result.attendance.data contains the array from Laravel's paginate()
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
        // Map stats if they exist, otherwise reset to defaults
        if($event.detail.stats) {
            stats = $event.detail.stats;
        } else {
            stats = { attendance_rate: '0%', total_present: 0, total_absent: 0, total_permission: 0 };
        }
        fetchRecords($event.detail.studentId);
    "
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-slate-50 w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden border border-white"
             @click.away="show = false">

            <div class="p-8 pb-4">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <button @click="show = false"
                                class="p-3 bg-white border border-gray-100 rounded-2xl text-slate-400 hover:text-blue-600 hover:border-blue-100 transition shadow-sm cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Attendance History</h1>
                            <p class="text-slate-400 font-bold text-sm">Student: <span class="text-blue-600" x-text="studentName"></span></p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <span x-text="courseName" class="px-4 py-2 bg-white text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-wider border border-slate-200 shadow-sm"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Average</p>
                        <p class="text-3xl font-black text-slate-800" x-text="stats.attendance_rate"></p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Present</p>
                        <p class="text-3xl font-black text-emerald-500" x-text="stats.total_present"></p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Permission</p>
                        <p class="text-3xl font-black text-amber-500" x-text="stats.total_permission"></p>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Absent</p>
                        <p class="text-3xl font-black text-rose-500" x-text="stats.total_absent"></p>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-t-[3rem] border-t border-gray-100 shadow-inner max-h-[50vh] overflow-y-auto">
                <div class="p-6 px-8 border-b border-gray-50 bg-slate-50 sticky top-0 z-10">
                    <h3 class="font-black text-slate-800 uppercase text-xs tracking-widest">Recent Records</h3>
                </div>

                <div class="divide-y divide-gray-50">
                    <template x-if="loading">
                        <div class="p-10 text-center">
                            <div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                            <p class="text-xs font-bold text-slate-400 mt-2 uppercase">Fetching Records...</p>
                        </div>
                    </template>

                    <template x-if="!loading && records.length > 0">
                        <template x-for="record in records" :key="record.id">
                            <div class="p-6 px-8 flex items-center justify-between hover:bg-slate-50 transition">
                                <div class="flex items-center gap-4">
                                    <div :class="{
                                        'bg-emerald-100 text-emerald-600': record.status === 'present',
                                        'bg-rose-100 text-rose-600': record.status === 'absent',
                                        'bg-amber-100 text-amber-600': record.status === 'permission'
                                    }" class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm">

                                        <template x-if="record.status === 'present'">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path d="m4.5 12.75 6 6 9-13.5" /></svg>
                                        </template>
                                        <template x-if="record.status === 'absent'">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                        </template>
                                        <template x-if="record.status === 'permission'">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path d="M12 7.5h.008v.008H12V7.5z" /></svg>
                                        </template>
                                    </div>

                                    <div>
                                        <p class="font-black text-slate-800 text-lg" x-text="new Date(record.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })"></p>
                                        <p :class="{
                                            'text-emerald-600': record.status === 'present',
                                            'text-rose-600': record.status === 'absent',
                                            'text-amber-600': record.status === 'permission'
                                        }" class="text-xs font-bold uppercase tracking-tighter">
                                            Status: <span x-text="record.status"></span>
                                            <span x-show="record.remark" class="text-slate-400" x-text="` (${record.remark})`"></span>
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-slate-400" x-text="record.class.class_time"></span>
                            </div>
                        </template>
                    </template>

                    <template x-if="!loading && records.length === 0">
                        <div class="p-20 text-center">
                            <p class="text-slate-400 font-bold">No attendance records found for this student.</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
