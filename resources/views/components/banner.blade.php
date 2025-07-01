@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<div x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
            class="fixed top-0 left-0 right-0 z-50 w-screen m-0"
            :class="{ 'bg-emerald-500': style == 'success' || style == 'save', 'bg-red-700': style == 'danger','bg-gray-400': style != 'success' && style != 'danger' && style != 'save' }"
            style="display: none;"
            x-show="show && message"
            x-cloak
            x-on:banner-message.window="style = event.detail.style;
                                        message = event.detail.message;
                                        show = true;"
            x-transition:enter="transition ease-in-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in-out duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            x-init="setTimeout(() => show = false, 5000)"
            >
    {{-- <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8"> --}}
    <div class="w-screen-xl mx-10 py-2">
        <div class="flex justify-between flex-wrap">
            <div class="w-0 flex-1 flex min-w-0 items-center">
                <span class="flex p-2 h-9 rounded-lg" :class="{ 'bg-emerald-600': style == 'success' || style == 'save', 'bg-red-600': style == 'danger' }">
                    <svg x-show="style == 'success' || style == 'save'" class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="style == 'danger'" class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <svg x-show="style != 'success' && style != 'danger' && style != 'save'" class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </span>
                <span class="">
                    <p class="ms-3 font-medium text-sm text-white truncate" x-html="message"></p>
                </span>
            </div>

            <div class="shrink-0 sm:ms-3 me-5 items-end">
                <button
                    type="button"
                    class="-me-1 flex p-2 rounded-md focus:outline-none sm:-me-2 transition hover:scale-105 hover:text-gray-100"
                    aria-label="Dismiss"
                    x-on:click="show = false">
                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
