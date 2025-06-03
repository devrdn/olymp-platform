@props(['success' => session('success'), 'error' => session('error'), 'warning' => session('warning')])

@if($success || $error || $warning)
    <div
        id="flash-message"
        class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white animate-slide-in w-80 max-w-[90vw] overflow-hidden"
        style="background-color:
            {{ $success ? '#38a169' : ($error ? '#e53e3e' : '#dd6b20') }};"
    >
        {{-- ✖ Крестик --}}
        <button onclick="closeFlash()" class="absolute top-2 right-3 text-white text-lg leading-none focus:outline-none">
            &times;
        </button>

        {{-- Сообщение --}}
        <div class="pr-6">
            {{ $success ?? $error ?? $warning }}
        </div>

        {{-- Прогресс-бар снизу --}}
        <div id="flash-progress" class="absolute bottom-0 left-0 h-1 w-full bg-white opacity-30 animate-progress-bar"></div>
    </div>

    <script>
        function closeFlash() {
            const el = document.getElementById('flash-message');
            if (el) {
                el.classList.add('animate-slide-out');
                setTimeout(() => el.remove(), 400);
            }
        }

        setTimeout(closeFlash, 3000);
    </script>

    <style>
        .animate-slide-in {
            opacity: 0;
            transform: translateY(-20px);
            animation: slideIn 0.4s ease-out forwards;
        }

        .animate-slide-out {
            opacity: 1;
            transform: translateY(0);
            animation: slideOut 0.4s ease-in forwards;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .animate-progress-bar {
            animation: progressAnim 3s linear forwards;
        }

        @keyframes progressAnim {
            from { width: 100%; }
            to   { width: 0%; }
        }
    </style>
@endif
