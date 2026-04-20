<x-guest-layout>
    <div class="h-screen flex items-center justify-center p-4 lg:p-8 font-sans overflow-hidden relative bg-slate-900">
        <!-- Background Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/bg.jpeg') }}" class="w-full h-full object-cover opacity-80" alt="Background">
            <div class="absolute inset-0 bg-white/80 backdrop-blur-sm"></div>
        </div>

        <!-- Main Card Container -->
        <div class="max-w-5xl w-full bg-white/95 backdrop-blur-md rounded-[32px] shadow-2xl shadow-black/20 overflow-hidden border border-white/20 flex flex-col lg:h-[700px] z-10 relative">
            <div class="grid lg:grid-cols-12 h-full">
                
                <!-- Left Panel: Login Form -->
                <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col justify-between">
                    
                    <!-- Logo Section -->
                    <div>
                        <a href="/">
                            <x-application-logo />
                        </a>
                    </div>

                    <div class="w-full max-w-sm mx-auto">
                        <!-- Header -->
                        <div class="mb-6 text-center lg:text-left">
                            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Sign in to Silica</h1>
                            <p class="text-slate-600 font-semibold text-sm">Siliwangi Integrated Cycle Accounting</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Social Buttons -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <button type="button" class="flex items-center justify-center py-2.5 px-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors">
                                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4 mr-2" alt="Google">
                                <span class="text-xs font-semibold text-slate-700">Google</span>
                            </button>
                            <button type="button" class="flex items-center justify-center py-2.5 px-4 border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors">
                                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-4 h-4 mr-2" alt="Facebook">
                                <span class="text-xs font-semibold text-slate-700">Facebook</span>
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-100"></div>
                            </div>
                            <div class="relative flex justify-center text-[10px] uppercase tracking-wider">
                                <span class="px-4 bg-white text-slate-400 font-bold">Or with email</span>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-tight mb-1.5 ml-1">Email Address</label>
                                <input id="email" 
                                       class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300 text-sm" 
                                       type="email" 
                                       name="email" 
                                       :value="old('email')" 
                                       required 
                                       autofocus 
                                       placeholder="nama@email.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <!-- Password -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5 ml-1">
                                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-tight">Password</label>
                                    @if (Route::has('password.request'))
                                        <a class="text-xs font-bold text-blue-600 hover:text-blue-700" href="{{ route('password.request') }}">
                                            Forgot?
                                        </a>
                                    @endif
                                </div>
                                <input id="password" 
                                       class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300 text-sm"
                                       type="password"
                                       name="password"
                                       required 
                                       placeholder="••••••••" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center ml-1">
                                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                                <span class="ms-2 text-xs font-medium text-slate-500">Keep me signed in</span>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                                    Sign In
                                </button>
                            </div>
                        </form>

                        <div class="mt-6 text-center text-xs">
                            <p class="text-slate-500">Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Sign Up</a></p>
                        </div>
                    </div>

                    <!-- Footer Section -->
                    <div class="flex items-center justify-between text-[10px] text-slate-300 uppercase tracking-widest font-bold pt-4">
                        <p>Privacy Policy</p>
                        <p>&copy; 2026 Silica.</p>
                    </div>
                </div>

                <!-- Right Panel: Design/Illustration -->
                <div class="hidden lg:block lg:col-span-5 bg-blue-600 relative overflow-hidden h-full">
                    <x-login-illustration />
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
