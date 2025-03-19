@php
        $sessionMessage = session('message');
        $sessionMessageType = session('messageType');
        $sessionIsSuccess = $sessionMessageType === 'success';
        $sessionMessageBg = $sessionIsSuccess ? 'bg-green-100' : 'bg-red-100';
        $sessionMessageBorder = $sessionIsSuccess ? 'border-green-400' : 'border-red-400';
        $sessionMessageText = $sessionIsSuccess ? 'text-green-700' : 'text-red-700';
    @endphp

    @if ($sessionMessage)
        <div class="w-full flex justify-center" x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
            x-transition:leave="transition-opacity duration-[3s] ease-in-out" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div
                class="fixed flex items-center mx-4 max-w-xl md:mx-auto mt-4 p-4 border-2 rounded-lg {{ $sessionMessageBg }} 
                        {{ $sessionMessageBorder }} {{ $sessionMessageText }}">
                <div class="flex items-center">
                    @if ($sessionIsSuccess)
                        <svg class="w-5 h-5 text-green-700 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a6 6 0 110 12 6 6 0 010-12z"
                                clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-red-700 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a6 6 0 110 12 6 6 0 010-12z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif

                    <p>{{ $sessionMessage }}</p>
                </div>
            </div>
        </div>
    @endif