{{-- notification used to send message after completing an event --}}

<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        trigger(msg, type = 'success') {
            this.message = msg;
            this.type = type;
            this.show = true;
            setTimeout(() => this.show = false, 3000);
        }
    }"
    @notify.window="trigger($event.detail.message, $event.detail.type)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-[-20px] opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-6 right-6 z-200 pointer-events-none"
    x-cloak
>
    <div
        :class="{
            'bg-slate-900': type === 'success',
            'bg-red-900': type === 'error'
        }"
        class="text-white px-6 py-4 rounded-4xl shadow-2xl flex items-center gap-4 border border-white/10 backdrop-blur-md"
    >
        {{-- Icon --}}
        <div
            :class="{
                'bg-green-500 shadow-green-500/40': type === 'success',
                'bg-red-500 shadow-red-500/40': type === 'error'
            }"
            class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg"
        >
            {{-- Success Icon (Check) --}}
            <svg x-show="type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            {{-- Error Icon (X) --}}
            <svg x-show="type === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <div>
            <p class="text-xs font-black uppercase tracking-widest text-white/40">System Message</p>
            <p class="text-sm font-bold" x-text="message"></p>
        </div>
    </div>
</div>
