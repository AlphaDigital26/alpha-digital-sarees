<div 
    x-data="{ show: false }" 
    @close-login-modal.window="show = false"
    @open-login-modal.window="show = true"
    x-show="show" 
    style="display: none;"
    {{-- Changed z-[100] to z-[9999] so it completely covers the z-1000 navbar --}}
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
>
    {{-- Made the height responsive (max-h-[95vh]) so it doesn't break on small laptops --}}
    <div 
        @click.outside="show = false"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-2xl flex max-w-[850px] w-full min-h-[500px] md:h-[550px] max-h-[95vh] overflow-hidden relative"
    >
        <button @click="show = false" class="absolute top-4 right-4 text-gray-500 hover:text-black z-20 transition-colors outline-none border-none bg-transparent cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <div class="hidden md:block md:w-[45%] relative bg-[#F4F0EB]">
            <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" class="w-full h-full object-cover" alt="Alpha Digital">
        </div>

        <div class="w-full md:w-[55%] flex flex-col pt-8 md:pt-10 px-8 md:px-12 pb-6 bg-white overflow-y-auto" style="-ms-overflow-style: none; scrollbar-width: none;">
            
            <div class="text-center mb-6 flex justify-center">
                 <h1 class="text-xl font-bold tracking-[0.2em] text-[#800020] uppercase m-0" style="font-family: 'Noto Serif', serif;">
                    ALPHA DIGITAL
                </h1>
            </div>

            @if($step == 1)
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Login / Sign Up</h2>
                <p class="text-[13px] text-gray-500 text-center mb-8 mt-0">Enter your log in details</p>

                <form wire:submit.prevent="sendOtp" class="space-y-6 m-0">
                    <div>
                        <label class="block text-[13px] font-bold text-black mb-2">Phone</label>
                        <div class="flex border border-gray-300 rounded-md focus-within:border-black transition-colors overflow-hidden h-[46px]">
                            <select wire:model="countryCode" class="bg-transparent border-r border-gray-300 px-3 text-[13px] font-medium text-black focus:outline-none cursor-pointer">
                                <option value="+91">🇮🇳 +91</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+44">🇬🇧 +44</option>
                            </select>
                            <input type="tel" wire:model="phone" class="w-full border-none px-3 focus:ring-0 text-[14px] outline-none font-medium text-black tracking-wide" maxlength="10">
                        </div>
                        @error('phone') <span class="text-red-500 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black border-none text-white h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-2 text-[15px] cursor-pointer">
                        Request OTP
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-[11px] text-gray-400 leading-relaxed m-0">
                            I accept that I have read & understood<br>
                            <a href="#" class="text-gray-500 hover:text-black">Privacy Policy</a> and <a href="#" class="text-gray-500 hover:text-black">T&Cs</a>.
                        </p>
                    </div>
                </form>

            @elseif($step == 2)
                <button wire:click="$set('step', 1)" class="text-gray-400 absolute top-6 left-6 flex items-center text-xs font-bold hover:text-black bg-transparent border-none cursor-pointer outline-none p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg> Back
                </button>
                
                <h2 class="text-lg font-bold text-black text-center mb-1 mt-0">Verify OTP</h2>
                <p class="text-[13px] text-gray-500 text-center mb-6 mt-0">Enter the 4-digit code sent to {{ $countryCode }} {{ $phone }}</p>

                @if (session()->has('test_otp'))
                    <div class="mb-6 p-2 bg-green-50 text-green-800 text-center text-xs font-bold rounded border border-green-200">
                        {{ session('test_otp') }}
                    </div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="space-y-6 m-0">
                    <div>
                        <input type="text" wire:model="otp" placeholder="----" class="w-full border-0 border-b border-gray-300 focus:border-black pb-2 text-center text-3xl tracking-[0.5em] font-bold focus:ring-0 outline-none transition-colors bg-transparent" maxlength="4">
                        @error('otp') <span class="text-red-500 text-[11px] font-bold mt-2 block text-center">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black text-white border-none h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors text-[15px] cursor-pointer">
                        Verify
                    </button>
                </form>

            @elseif($step == 3)
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
                        <input type="email" wire:model="email" placeholder="Email Id" class="w-full border border-gray-300 rounded-md h-[40px] px-3 text-[13px] focus:border-black outline-none transition-colors">
                        @error('email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[12px] font-bold text-black mb-1">Phone</label>
                        <input type="text" disabled value="{{ $countryCode }}{{ $phone }}" class="w-full border border-gray-200 rounded-md h-[40px] px-3 text-[13px] bg-gray-50 text-gray-500 cursor-not-allowed">
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
                            <span class="text-[11px] text-gray-600">I agree to the Terms of Service.</span>
                        </label>
                        @error('agree_tos') <span class="text-red-500 text-[10px] block mt-0">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black text-white border-none h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-4 text-[15px] cursor-pointer">
                        Update
                    </button>
                </form>

            @elseif($step == 4)
                <div class="text-center flex flex-col items-center justify-center h-full pb-10">
                    <h2 class="text-xl font-bold text-black mb-3 leading-tight text-center mt-0">
                        🎉 Congratulations!<br>Verification Successful
                    </h2>
                    
                    <button wire:click="closePopup" class="w-full bg-black text-white border-none h-[46px] font-medium rounded-md hover:bg-gray-800 transition-colors mt-8 text-[15px] cursor-pointer">
                        Start Shopping
                    </button>
                </div>
            @endif

        </div>
    </div>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>