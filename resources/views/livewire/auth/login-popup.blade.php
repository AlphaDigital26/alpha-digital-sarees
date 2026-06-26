<div 
    x-data="{
        show: false,
        _scrollY: 0,
        _wheelHandler: null,
        _touchHandler: null,
        _keyHandler: null,

        _blockScroll(e) {
            e.preventDefault();
            e.stopPropagation();
        },

        _blockKeys(e) {
            const scrollKeys = [32, 33, 34, 35, 36, 37, 38, 39, 40];
            if (scrollKeys.includes(e.keyCode)) {
                /* only block if the focus is NOT inside an input/textarea/select */
                const tag = document.activeElement && document.activeElement.tagName;
                if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) {
                    e.preventDefault();
                }
            }
        },

        _openModal() {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
            document.documentElement.classList.add('modal-open');

            /* Block wheel / touch / keyboard scroll on the backdrop element */
            const el = this.$el;
            this._wheelHandler = (e) => {
                if (!el.contains(e.target)) return;
                /* Allow scrolling inside the inner modal panel */
                const inner = el.querySelector('.modal-inner-scroll');
                if (inner && inner.contains(e.target)) return;
                e.preventDefault();
                e.stopPropagation();
            };
            this._touchHandler = (e) => {
                if (!el.contains(e.target)) return;
                const inner = el.querySelector('.modal-inner-scroll');
                if (inner && inner.contains(e.target)) return;
                e.preventDefault();
                e.stopPropagation();
            };
            this._keyHandler = this._blockKeys.bind(this);

            el.addEventListener('wheel',      this._wheelHandler, { passive: false, capture: true });
            el.addEventListener('touchmove',  this._touchHandler, { passive: false, capture: true });
            window.addEventListener('keydown', this._keyHandler,  { capture: true });

            this.show = true;
        },

        _closeModal() {
            if (typeof $wire !== 'undefined' && $wire.step == 4) {
                window.location.href = $wire.redirectUrl || '/';
                return;
            }

            this.show = false;

            /* Remove event listeners */
            const el = this.$el;
            if (this._wheelHandler)  el.removeEventListener('wheel',      this._wheelHandler, { capture: true });
            if (this._touchHandler)  el.removeEventListener('touchmove',  this._touchHandler, { capture: true });
            if (this._keyHandler)   window.removeEventListener('keydown', this._keyHandler,   { capture: true });
            this._wheelHandler = this._touchHandler = this._keyHandler = null;

            /* Restore body */
            document.body.classList.remove('modal-open');
            document.documentElement.classList.remove('modal-open');
            document.body.style.overflow = '';
        }
    }"
    x-init="
        window.addEventListener('open-login-modal',  () => _openModal());
        window.addEventListener('close-login-modal', () => _closeModal());
    "
    @close-login-modal.window="_closeModal()"
    @open-login-modal.window="_openModal()"
    x-show="show" 
    style="display: none;"
    {{-- z-[9999] so it completely covers the z-1000 navbar --}}
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
>
    {{-- Made the height responsive (max-h-[95vh]) so it doesn't break on small laptops --}}
    <div 
        @click.outside="_closeModal()"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-2xl flex max-w-[850px] w-full min-h-[500px] md:h-[550px] max-h-[95vh] overflow-hidden relative"
    >
        <div class="hidden md:block md:w-[45%] relative bg-[#F4F0EB]">
            <img src="{{ asset('images/LoginPopup.webp') }}" class="w-full h-full object-cover" alt="Alpha Digital">
        </div>

        <div class="modal-inner-scroll overscroll-contain w-full md:w-[55%] flex flex-col pt-8 md:pt-10 px-8 md:px-12 pb-6 bg-white overflow-y-auto relative" style="-ms-overflow-style: none; scrollbar-width: none;">
            
            <button @click="_closeModal()" class="absolute top-6 right-6 text-gray-500 hover:text-black z-20 transition-colors outline-none border-none bg-transparent cursor-pointer" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>

            <div class="text-center mb-6 mt-6 md:mt-2 flex justify-center">
                 <h1 class="text-xl font-bold tracking-[0.2em] text-[#800020] uppercase m-0" style="font-family: 'Noto Serif', serif;">
                    ALPHA DIGITAL
                </h1>
            </div>

            @if($step == 1)
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Login / Sign Up</h2>
                <p class="text-[13px] text-gray-500 text-center mb-8 mt-0">Enter your log in details</p>

                <form wire:submit.prevent="checkEmail" class="space-y-6 m-0">
                    <div>
                        <label class="block text-[13px] font-bold text-black mb-2">Email Address</label>
                        <input type="email" wire:model="email" placeholder="Enter your email" class="w-full border border-gray-300 rounded-md h-[46px] px-3 text-[14px] focus:border-black outline-none transition-colors">
                        @error('email') <span class="text-red-500 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black border-none text-white h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-2 text-[15px] cursor-pointer">
                        Continue
                    </button>

                    <div class="relative mt-6 mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500 text-[12px]">Or continue with</span>
                        </div>
                    </div>

                    <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 h-[46px] transition-colors no-underline cursor-pointer">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            <path d="M1 1h22v22H1z" fill="none"/>
                        </svg>
                        Sign in with Google
                    </a>
                    <div class="text-center mt-6">
                        <p class="text-[11px] text-gray-400 leading-relaxed m-0">
                            By continuing, you agree to Alpha Digital's<br>
                            <a href="#" class="text-gray-500 hover:text-black">Conditions of Use</a> and <a href="#" class="text-gray-500 hover:text-black">Privacy Notice</a>.
                        </p>
                    </div>
                </form>

            @elseif($step == 2)
                <button wire:click="$set('step', 1)" class="text-gray-400 absolute top-6 left-6 flex items-center text-xs font-bold hover:text-black bg-transparent border-none cursor-pointer outline-none p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg> Back
                </button>
                
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Sign-In</h2>
                <p class="text-[13px] text-gray-500 text-center mb-6 mt-0 flex items-center justify-center gap-2">
                    {{ $email }}
                    <a href="#" wire:click.prevent="$set('step', 1)" class="text-[#800020] hover:underline font-bold text-[11px]">Change</a>
                </p>

                <form wire:submit.prevent="authenticate" class="space-y-6 m-0">
                    @error('email') 
                        <div class="bg-red-50 border border-red-100 text-red-600 p-3 rounded-md text-[13px] font-medium text-center">
                            {{ $message }}
                        </div>
                    @enderror
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[13px] font-bold text-black">Password</label>
                            <a href="#" wire:click.prevent="$set('step', 5)" class="text-[11px] text-[#800020] hover:underline font-medium">Forgot password?</a>
                        </div>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" class="w-full border border-gray-300 rounded-md h-[46px] pl-3 pr-10 focus:border-black transition-colors outline-none">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800020] transition-colors focus:outline-none flex items-center bg-transparent border-none p-0 cursor-pointer" aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-[11px] font-bold mt-2 block">{{ $message }}</span> @enderror
                        
                        <div class="mt-4 flex items-center">
                            <input type="checkbox" wire:model="remember" id="remember_me" class="w-4 h-4 border-gray-300 rounded text-[#800020] focus:ring-[#800020]">
                            <label for="remember_me" class="ml-2 block text-[13px] text-gray-700 m-0">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-black text-white border-none h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors text-[15px] cursor-pointer">
                        Sign In
                    </button>
                </form>

            @elseif($step == 3)
                <button wire:click="$set('step', 1)" class="text-gray-400 absolute top-6 left-6 flex items-center text-xs font-bold hover:text-black bg-transparent border-none cursor-pointer outline-none p-0 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg> Back
                </button>
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Enter Account Details</h2>
                <p class="text-[13px] text-gray-500 text-center mb-6 mt-0">Enter below details and update your account</p>

                <form wire:submit.prevent="saveDetails" class="space-y-4 m-0">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[12px] font-bold text-black mb-1">First Name*</label>
                            <input type="text" wire:model="first_name" placeholder="Enter your first name" class="w-full border border-gray-300 rounded-md h-[40px] px-3 text-[13px] focus:border-black outline-none transition-colors">
                            @error('first_name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-black mb-1">Last Name*</label>
                            <input type="text" wire:model="last_name" placeholder="Enter your last name" class="w-full border border-gray-300 rounded-md h-[40px] px-3 text-[13px] focus:border-black outline-none transition-colors">
                            @error('last_name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Email*</label>
                        <input type="email" readonly tabindex="-1" autocomplete="username" value="{{ $email }}" class="w-full border border-gray-200 rounded-md h-[40px] px-3 text-[13px] bg-gray-50 text-gray-500 cursor-not-allowed outline-none">
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Mobile number*</label>
                        <input type="tel" autocomplete="tel" wire:model="phone" placeholder="Mobile number" class="w-full border border-gray-300 rounded-md h-[40px] px-3 text-[13px] focus:border-black outline-none transition-colors">
                        @error('phone') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Password*</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" autocomplete="new-password" placeholder="At least 6 characters" class="w-full border border-gray-300 rounded-md h-[40px] pl-3 pr-10 text-[13px] focus:border-black outline-none transition-colors">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800020] transition-colors focus:outline-none flex items-center bg-transparent border-none p-0 cursor-pointer" aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Re-enter Password*</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" autocomplete="new-password" class="w-full border border-gray-300 rounded-md h-[40px] pl-3 pr-10 text-[13px] focus:border-black outline-none transition-colors">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800020] transition-colors focus:outline-none flex items-center bg-transparent border-none p-0 cursor-pointer" aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Date of Birth*</label>
                        <input type="date" wire:model="dob" class="w-full border border-gray-300 rounded-md h-[40px] px-3 text-[13px] focus:border-black outline-none text-gray-600 transition-colors">
                        @error('dob') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Gender*</label>
                        <select wire:model="gender" class="w-full border border-gray-300 rounded-md h-[40px] px-3 text-[13px] focus:border-black outline-none text-gray-600 bg-white transition-colors">
                            <option value="">Gender</option>
                            <option value="female">Female</option>
                            <option value="male">Male</option>
                            <option value="other">Other</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer m-0">
                            <input type="checkbox" wire:model="subscribe" class="w-3.5 h-3.5 border-gray-300 rounded text-black focus:ring-black">
                            <span class="text-[11px] text-gray-600">Subscribe to our newsletter for exclusive content and news.</span>
                        </label>
                        
                        <label class="flex items-center gap-2 cursor-pointer m-0">
                            <input type="checkbox" wire:model="agree_tos" class="w-3.5 h-3.5 border-gray-300 rounded text-black focus:ring-black">
                            <span class="text-[11px] text-gray-600">I agree to the Terms of Service.*</span>
                        </label>
                        @error('agree_tos') <span class="text-red-500 text-[10px] block mt-0">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black text-white border-none h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-4 text-[15px] cursor-pointer">
                        Register
                    </button>
                </form>

            @elseif($step == 4)
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-[#F4F0EB] rounded-full flex items-center justify-center mx-auto mb-6 text-[#800020]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-black mb-2" style="font-family: 'Noto Serif', serif;">Welcome to ALPHA DIGITAL</h2>
                    <p class="text-gray-500 mb-8 px-4 text-[13px] leading-relaxed">Discover timeless sarees, handcrafted elegance, and curated collections inspired by Indian heritage.</p>
                    <a href="{{ $redirectUrl }}" class="bg-[#800020] hover:bg-[#5c0017] text-white transition-all duration-300 tracking-[0.15em] uppercase border-none h-[46px] px-8 font-medium rounded-md cursor-pointer flex items-center justify-center text-[13px] no-underline">
                        START SHOPPING
                    </a>
                </div>

            @elseif($step == 5)
                <button wire:click="$set('step', 1)" class="text-gray-400 absolute top-6 left-6 flex items-center text-xs font-bold hover:text-black bg-transparent border-none cursor-pointer outline-none p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg> Back
                </button>
                
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Reset Password</h2>
                <p class="text-[13px] text-gray-500 text-center mb-8 mt-0">Enter your email to receive a password reset link.</p>

                <form wire:submit.prevent="sendResetLink" class="space-y-6 m-0">
                    <div>
                        <label class="block text-[13px] font-bold text-black mb-2">Email Address</label>
                        <input type="email" wire:model="email" placeholder="Enter your email" class="w-full border border-gray-300 rounded-md h-[46px] px-3 text-[14px] focus:border-black outline-none transition-colors">
                        @error('email') <span class="text-red-500 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black border-none text-white h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-2 text-[15px] cursor-pointer" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sendResetLink">Send Reset Link</span>
                        <span wire:loading wire:target="sendResetLink">Sending...</span>
                    </button>
                </form>

            @elseif($step == 6)
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h2 class="text-xl font-bold text-black mb-3">Link Sent!</h2>
                    <p class="text-[13px] text-gray-500 leading-relaxed max-w-[280px] mx-auto mb-8">
                        We've sent a password reset link to <span class="font-bold text-black">{{ $email }}</span>. Please check your inbox.
                    </p>
                    <button @click="_closeModal()" class="w-full bg-black text-white border-none h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors text-[15px] cursor-pointer">
                        Close
                    </button>
                </div>

            @elseif($step == 7)
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Verify Your Email</h2>
                <p class="text-[13px] text-gray-500 text-center mb-2 mt-0">
                    We've sent a 6-digit code to <span class="font-bold text-black">{{ $email }}</span>
                </p>
                
                @if (session()->has('otp_message'))
                    <div class="mb-4 text-center text-[12px] font-medium text-green-600 bg-green-50 p-2 rounded">
                        {{ session('otp_message') }}
                    </div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="space-y-6 m-0 mt-6">
                    <div>
                        <label class="block text-[13px] font-bold text-black mb-2">6-Digit Code</label>
                        <input type="text" wire:model="otp_code" maxlength="6" placeholder="Enter code" class="w-full border border-gray-300 rounded-md h-[46px] px-3 text-[14px] focus:border-black outline-none transition-colors text-center tracking-[0.5em] font-mono font-bold">
                        @error('otp_code') <span class="text-red-500 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black border-none text-white h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-2 text-[15px] cursor-pointer" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="verifyOtp">Verify</span>
                        <span wire:loading wire:target="verifyOtp">Verifying...</span>
                    </button>

                    <div class="text-center mt-6" 
                         x-data="{ 
                             timer: 30, 
                             interval: null,
                             startTimer() {
                                 this.timer = 30;
                                 clearInterval(this.interval);
                                 this.interval = setInterval(() => {
                                     if (this.timer > 0) {
                                         this.timer--;
                                     } else {
                                         clearInterval(this.interval);
                                     }
                                 }, 1000);
                             }
                         }" 
                         x-init="
                             startTimer();
                             $wire.on('otp-resent', () => startTimer());
                         ">
                        <p class="text-[12px] text-gray-500 m-0">
                            Didn't receive the code? 
                            <button type="button" x-show="timer === 0" wire:click="resendOtp" class="text-black hover:text-[#800020] font-bold bg-transparent border-none cursor-pointer p-0 underline ml-1">
                                Resend
                            </button>
                            <span x-show="timer > 0" x-cloak class="text-gray-400 font-bold ml-1">
                                Resend in <span x-text="timer"></span>s
                            </span>
                        </p>
                    </div>
                </form>
            @endif

        </div>
    </div>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>