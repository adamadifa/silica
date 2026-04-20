<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Silica') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Flatpickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            /* Custom Flatpickr Styling */
            .flatpickr-calendar {
                background: #ffffff;
                box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.15);
                border: 0;
                border-radius: 28px;
                padding: 12px;
                width: 320px !important; /* Fix Saturday cut-off */
            }
            .flatpickr-days {
                width: 300px !important;
            }
            .dayContainer {
                width: 300px !important;
                min-width: 300px !important;
                max-width: 300px !important;
            }
            .flatpickr-day.selected {
                background: #2563eb !important;
                border-color: #2563eb !important;
                border-radius: 14px;
                box-shadow: 0 4px 6px -1px rgb(37 99 235 / 0.4);
            }
            .flatpickr-day:hover {
                background: #eff6ff !important;
                border-radius: 14px;
            }
            .flatpickr-months .flatpickr-month {
                color: #0f172a;
                fill: #0f172a;
                height: 40px;
            }
            .flatpickr-current-month .flatpickr-monthDropdown-months {
                font-weight: 800;
                font-size: 1.1em;
            }
            .flatpickr-weekday {
                color: #94a3b8 !important;
                font-weight: 700;
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }
        </style>
    </head>
    <body class="h-full antialiased text-slate-900" x-data="{ open: false }">
        <div class="flex h-full overflow-hidden">
            
            <!-- Sidebar Navigation -->
            <x-sidebar />

            <!-- Main Content Area -->
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden lg:pl-72">
                
                <!-- Header -->
                <x-header />

                <!-- Flash Messages -->
                <div class="px-8 mt-2">
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-100 flex items-center shadow-sm animate-fade-in-down" role="alert">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-100 flex items-center shadow-sm animate-fade-in-down" role="alert">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Page Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none scrollbar-hide">
                    <div class="pt-2 pb-6 px-8 max-w-[1600px] mx-auto">
                        @if(isset($perusahaan))
                            <x-inspection-banner :perusahaan="$perusahaan" />
                        @endif
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr(".datepicker", {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d F Y",
                    allowInput: true,
                    disableMobile: true,
                });
            });
        </script>
    @stack('scripts')
</body>
</html>
