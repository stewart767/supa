<x-public-layout title="Track Application Status & Resume Wizard - SUPA University">

    <section class="relative text-white py-20 text-center space-y-4 border-b border-slate-200 bg-cover bg-center bg-no-repeat bg-gradient-to-r from-slate-950 via-blue-950 to-slate-900"
             @if(\App\Models\Setting::get('banner_track')) style="background-image: linear-gradient(to right, rgba(2, 6, 23, 0.95), rgba(30, 58, 138, 0.85)), url('{{ asset('storage/' . \App\Models\Setting::get('banner_track')) }}');" @endif>
        <span class="px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-extrabold uppercase tracking-wider">
            Real-Time Verification & Resume Portal
        </span>
        <h1 class="hero-title font-extrabold tracking-tight">Track & Continue Application</h1>
        <p class="body-text text-slate-300 max-w-2xl mx-auto text-xs sm:text-sm">
            Enter your Phone Number, Control Number, or Application Number to check selection status or verify identity and resume your admission wizard.
        </p>
    </section>

    <section class="py-16 bg-slate-50" x-data="{ 
        appNumber: '', 
        result: null, 
        error: null, 
        loading: false, 
        otpLoading: false, 
        otpError: null, 
        otpSuccess: null, 
        showOtpForm: false, 
        otpCode: '', 
        otpSent: false 
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left/Main Column: Tracking Form and Results -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-200 space-y-6">
                        
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                                <span class="text-2xl">🔍</span> Fuatilia Ombi Lako (Track Application)
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Ingiza Namba ya Simu, Namba ya Malipo (Control Number), au Namba ya Ombi kutazama taarifa na kuendelea na udahili wako.
                            </p>
                        </div>

                        <!-- Query Form -->
                        <form @submit.prevent="
                            loading = true; error = null; result = null; showOtpForm = false; otpSent = false; otpCode = ''; otpError = null; otpSuccess = null;
                            axios.post('{{ url('/api/v1/public/track-application') }}', { application_number: appNumber })
                                .then(res => { result = res.data; loading = false; })
                                .catch(err => { error = err.response?.data?.message || 'No active application record was found.'; loading = false; })
                        " class="space-y-4">
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                    Phone Number, Control Number, or Application #
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" x-model="appNumber" required placeholder="e.g., 0712345678 or SUPA-2026-000001" 
                                           class="w-full pl-12 pr-5 py-4 rounded-2xl border border-slate-350 bg-slate-50 text-slate-950 font-bold text-base focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
                                </div>
                            </div>

                            <button type="submit" :disabled="loading" class="w-full gradient-btn py-4 rounded-2xl text-white font-extrabold text-sm shadow-xl flex items-center justify-center gap-2">
                                <span x-show="!loading" class="flex items-center gap-2">
                                    <span>Tafuta Ombi / Search Application</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Inatafuta... (Checking database...)</span>
                                </span>
                            </button>
                        </form>

                        <!-- Error Alert -->
                        <div x-show="error" class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-bold flex items-center gap-2" x-cloak>
                            <svg class="w-5 h-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span x-text="error"></span>
                        </div>

                        <!-- Result Card -->
                        <div x-show="result" class="p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-6 shadow-sm" x-cloak>
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-b border-slate-200 pb-4">
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-0.5">Application Number (Namba ya Ombi)</span>
                                    <span class="text-xl font-black text-blue-900 tracking-tight" x-text="result?.application_number"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1 text-left sm:text-right">Status (Hali ya Ombi)</span>
                                    <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-black uppercase shadow-sm tracking-wider border"
                                          :class="{ 
                                              'bg-amber-50 text-amber-800 border-amber-200': result?.status === 'Draft' || result?.status === 'Pending Payment' || result?.status === 'PAYMENT_PENDING' || result?.status === 'IN_PROGRESS', 
                                              'bg-emerald-50 text-emerald-800 border-emerald-200': result?.status === 'Approved' || result?.status === 'SUBMITTED' || result?.status === 'Under Review', 
                                              'bg-red-50 text-red-800 border-red-200': result?.status === 'Rejected' || result?.status === 'Expired' 
                                          }"
                                          x-text="result?.status"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs font-semibold text-slate-700">
                                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                    <span class="text-slate-400 block text-[10px] uppercase font-bold mb-1">Chosen Programme / Programu</span>
                                    <span class="font-extrabold text-slate-900 text-sm leading-snug" x-text="result?.programme"></span>
                                </div>
                                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                    <span class="text-slate-400 block text-[10px] uppercase font-bold mb-1">Admission Category / Kundi la Udahili</span>
                                    <span class="font-extrabold text-slate-900 text-sm leading-snug" x-text="result?.admission_category"></span>
                                </div>
                                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                    <span class="text-slate-400 block text-[10px] uppercase font-bold mb-1">Fee Payment Status / Hali ya Malipo</span>
                                    <span class="font-black text-sm uppercase leading-snug" :class="result?.payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-500'" x-text="result?.payment_status"></span>
                                </div>
                                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                    <span class="text-slate-400 block text-[10px] uppercase font-bold mb-1">Last Step Progress / Hatua Iliyofikiwa</span>
                                    <span class="font-extrabold text-slate-900 text-sm leading-snug" x-text="'Step ' + (result?.current_step || 1) + ' (' + (result?.completion_percentage || 0) + '%)'"></span>
                                </div>
                            </div>

                            <!-- Incomplete / Resume Flow -->
                            <template x-if="result?.status === 'Draft' || result?.status === 'IN_PROGRESS' || result?.status === 'Pending Payment' || result?.status === 'PAYMENT_PENDING'">
                                <div class="border-t border-slate-200 pt-6 space-y-4">
                                    <div class="p-4 bg-amber-50/80 rounded-2xl border border-amber-200/60 text-xs text-amber-900 leading-relaxed font-medium">
                                        ⚠️ <strong>Ombi lako halijakamilika.</strong> Unaweza kuendelea kujaza ombi hili kutoka hatua uliyoiacha. Bofya kitufe kilicho chini ili kuendelea na usajili.
                                        <br><br>
                                        (<strong>Your application is incomplete.</strong> You can resume filling it. Click below to continue with the registration.)
                                    </div>

                                    <!-- Direct Resume button -->
                                    <button @click="
                                        otpLoading = true; otpError = null;
                                        axios.post('{{ url('/api/v1/public/resume-direct') }}', { application_id: result.application_id, user_id: result.user_id })
                                            .then(res => { window.location.href = res.data.redirect_url; })
                                            .catch(err => { otpError = err.response?.data?.message || 'Failed to resume application. Try again later.'; otpLoading = false; })
                                    " :disabled="otpLoading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-4 rounded-2xl text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                                        <span x-show="!otpLoading" class="flex items-center gap-2">
                                            <span>🚀 Endelea na Usajili (Resume Registration)</span>
                                        </span>
                                        <span x-show="otpLoading" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Inapakia... (Connecting...)</span>
                                        </span>
                                    </button>

                                    <!-- Error Message -->
                                    <div x-show="otpError" class="p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-bold" x-cloak x-text="otpError"></div>

                                    <!-- Detailed Payment Guideline from PDF -->
                                    <template x-if="result?.payment">
                                        <div class="border-t border-slate-200 pt-6 space-y-6">
                                            <!-- NMB Control Number Box -->
                                            <div class="p-6 rounded-3xl bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white border border-blue-950 shadow-xl space-y-4 text-left">
                                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-white/20 pb-4">
                                                    <div class="space-y-1">
                                                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-amber-400 block">NMB Control Number</span>
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-2xl font-black text-white tracking-wide font-mono" x-text="result?.payment?.control_number || 'Inazalishwa...'"></span>
                                                            <button type="button" 
                                                                    x-show="result?.payment?.control_number"
                                                                    @click="navigator.clipboard.writeText(result.payment.control_number).then(() => alert('Namba ya Control Number imenakiliwa: ' + result.payment.control_number))"
                                                                    class="px-2.5 py-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold flex items-center gap-1 transition-all"
                                                                    title="Copy Control Number">
                                                                📋 Copy
                                                            </button>
                                                        </div>
                                                        <span class="text-[9px] font-bold uppercase tracking-wider mt-1 block"
                                                              :class="result?.payment?.singida_synced ? 'text-emerald-300' : 'text-amber-300'"
                                                              x-text="result?.payment?.singida_synced ? '✓ Control Number Imethibitishwa' : 'Inatayarishwa kwenye mtandao wa NMB...'"></span>
                                                    </div>
                                                    <div class="text-left sm:text-right">
                                                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-blue-200 block">Kiasi cha Ada ya Maombi</span>
                                                        <span class="text-xl font-black text-amber-400">TZS 20,000/=</span>
                                                    </div>
                                                </div>

                                                <!-- View / Download PDF Action bar -->
                                                <div class="flex flex-wrap items-center gap-3 pt-1">
                                                    <a href="{{ route('public.payment-guideline') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 hover:border-white/20 text-[11px] font-bold text-white transition-all text-center flex items-center gap-1.5 cursor-pointer">
                                                        👁️ View Guideline (PDF)
                                                    </a>
                                                    <a href="{{ route('public.payment-guideline') }}?download=1" target="_blank" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-[11px] font-black text-slate-950 transition-all text-center flex items-center gap-1.5 cursor-pointer">
                                                        📥 Download PDF Instructions
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- PDF Step-by-Step Instructions Container -->
                                            <div x-data="{ activeTab: 'mobile' }" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm text-left space-y-4">
                                                <div class="border-b border-slate-100 pb-3">
                                                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                                        <span>📋</span> Maelekezo ya Hatua kwa Hatua (PDF Guidelines)
                                                    </h3>
                                                </div>

                                                <!-- Tab Navigation -->
                                                <div class="flex flex-wrap border-b border-slate-150 gap-1">
                                                    <button type="button" @click="activeTab = 'mobile'" :class="activeTab === 'mobile' ? 'border-blue-600 text-blue-600 bg-blue-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="px-4 py-2.5 rounded-t-xl border-b-2 text-xs font-bold transition-all cursor-pointer">
                                                        📱 Mobile Money
                                                    </button>
                                                    <button type="button" @click="activeTab = 'nmb_mkononi'" :class="activeTab === 'nmb_mkononi' ? 'border-blue-600 text-blue-600 bg-blue-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="px-4 py-2.5 rounded-t-xl border-b-2 text-xs font-bold transition-all cursor-pointer">
                                                        🏦 NMB Mkononi (USSD & App)
                                                    </button>
                                                    <button type="button" @click="activeTab = 'bank'" :class="activeTab === 'bank' ? 'border-blue-600 text-blue-600 bg-blue-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="px-4 py-2.5 rounded-t-xl border-b-2 text-xs font-bold transition-all cursor-pointer">
                                                        🏢 Tawi la NMB / Wakala
                                                    </button>
                                                </div>

                                                <!-- Tab Contents -->
                                                <div class="text-xs text-slate-700 space-y-4 pt-1">
                                                    
                                                    <!-- Tab 1: Mobile Money -->
                                                    <div x-show="activeTab === 'mobile'" class="space-y-4" x-cloak>
                                                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-800 text-[11px] leading-relaxed">
                                                            ℹ️ Namba ya Kampuni (Business Number) ni <strong>888999</strong> na Reference ni Namba ya Malipo (Control Number) inayoanza na <strong>SASXXXXXXXXXXX</strong>.
                                                        </div>

                                                        <!-- Vodacom M-Pesa -->
                                                        <div class="space-y-1.5">
                                                            <h4 class="font-extrabold text-orange-600 text-[11px] uppercase tracking-wider">Vodacom M-Pesa</h4>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Piga <strong>*150*00#</strong>, kisha chagua <strong>4 [Lipa kwa M-Pesa]</strong></li>
                                                                <li>Chagua <strong>4 [Weka namba ya kampuni / Enter Business Number]</strong></li>
                                                                <li>Ingiza Namba ya Kampuni: <strong>888999</strong></li>
                                                                <li>Ingiza Kumbukumbu ya Malipo: Jaza Namba ya Malipo inayoanza na <strong>SASXXXXXXXXXXX</strong></li>
                                                                <li>Weka kiasi: <strong>TZS 20,000/=</strong>, kisha weka PIN na uthibitishe malipo.</li>
                                                            </ol>
                                                        </div>

                                                        <!-- Tigo Pesa -->
                                                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                                            <h4 class="font-extrabold text-sky-600 text-[11px] uppercase tracking-wider">Tigo Pesa</h4>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Piga <strong>*150*01#</strong>, kisha chagua <strong>4 [Lipia Bili / Pay Bills]</strong></li>
                                                                <li>Chagua <strong>3 [Ingiza Namba ya Kampuni / Enter Business Number]</strong></li>
                                                                <li>Ingiza Namba ya Kampuni: <strong>888999</strong></li>
                                                                <li>Ingiza Kumbukumbu ya Malipo: Jaza Namba ya Malipo inayoanza na <strong>SASXXXXXXXXXXX</strong></li>
                                                                <li>Weka kiasi: <strong>TZS 20,000/=</strong>, kisha weka PIN na uthibitishe malipo.</li>
                                                            </ol>
                                                        </div>

                                                        <!-- Airtel Money -->
                                                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                                            <h4 class="font-extrabold text-red-600 text-[11px] uppercase tracking-wider">Airtel Money</h4>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Piga <strong>*150*60#</strong>, kisha chagua <strong>5 [Lipia Bili / Pay Bills]</strong></li>
                                                                <li>Chagua <strong>4 [Ingiza Namba ya Kampuni / Enter Business Number]</strong></li>
                                                                <li>Ingiza Namba ya Kampuni: <strong>888999</strong></li>
                                                                <li>Ingiza Kumbukumbu ya Malipo: Jaza Namba ya Malipo inayoanza na <strong>SASXXXXXXXXXXX</strong></li>
                                                                <li>Weka kiasi: <strong>TZS 20,000/=</strong>, kisha weka PIN na uthibitishe malipo.</li>
                                                            </ol>
                                                        </div>
                                                    </div>

                                                    <!-- Tab 2: NMB Mkononi -->
                                                    <div x-show="activeTab === 'nmb_mkononi'" class="space-y-4" x-cloak>
                                                        <!-- NMB Mkononi USSD -->
                                                        <div class="space-y-1.5">
                                                            <h4 class="font-extrabold text-blue-900 text-[11px] uppercase tracking-wider">NMB Mkononi (*150*66#)</h4>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Dial/Piga <strong>*150*66#</strong> kwenye simu yako.</li>
                                                                <li>Weka namba ya siri (PIN) ya NMB Mkononi.</li>
                                                                <li>Chagua <strong>2 [LIPA BILI / PAY BILLS]</strong>.</li>
                                                                <li>Chagua <strong>5 [CHAGUA BIASHARA / CHOOSE BUSINESS]</strong>.</li>
                                                                <li>Chagua <strong>3 [WEKA NAMBA YA BIASHARA / ENTER BUSINESS NUMBER]</strong>.</li>
                                                                <li>Weka namba ya biashara: <strong>999999</strong>.</li>
                                                                <li>Weka kumbukumbu (Reference number): Ingiza Namba ya Malipo (e.g. <strong>SASXXXXXX</strong>).</li>
                                                                <li>Ingiza kiasi: <strong>TZS 20,000/=</strong> kisha thibitisha kwa kuweka PIN yako.</li>
                                                            </ol>
                                                        </div>

                                                        <!-- NMB Mkononi App -->
                                                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                                            <h4 class="font-extrabold text-blue-900 text-[11px] uppercase tracking-wider">NMB Mkononi App</h4>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Fungua NMB Mkononi App na uingize PIN yako.</li>
                                                                <li>Chagua <strong>Bill Payment (Malipo ya Bili)</strong>.</li>
                                                                <li>Chagua <strong>Other Billers (Watoa Bili Wengine)</strong>.</li>
                                                                <li>Kwenye sanduku la utafutaji (Search), tafuta na uchague <strong>SINGIDA TEACHERS COLLEGE</strong>.</li>
                                                                <li>Weka Reference Number: Jaza Namba yako ya Malipo inayoanza na <strong>SASXXXXXXXXXXX</strong>.</li>
                                                                <li>Ingiza kiasi cha malipo, thibitisha taarifa na ukamilishe malipo.</li>
                                                            </ol>
                                                        </div>
                                                    </div>

                                                    <!-- Tab 3: NMB Branch / Wakala -->
                                                    <div x-show="activeTab === 'bank'" class="space-y-4" x-cloak>
                                                        <!-- NMB Branches -->
                                                        <div class="space-y-1.5">
                                                            <h4 class="font-extrabold text-blue-950 text-[11px] uppercase tracking-wider">Kupitia Tawi la NMB (Branch Counter)</h4>
                                                            <p class="text-slate-500 text-[10px] font-semibold leading-relaxed mb-1">
                                                                Jaza karatasi ya malipo (Bills Payment Slip) inayopatikana katika matawi yote ya NMB kote nchini:
                                                            </p>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Andika <strong>Bill Number</strong>: Jaza Namba ya Malipo inayoanza na <strong>SASXXXXXXXXXXX</strong></li>
                                                                <li>Andika <strong>Biller Name</strong>: Jaza <strong>SINGIDA TEACHERS COLLEGE</strong></li>
                                                                <li>Jaza kiasi: <strong>TZS 20,000/=</strong></li>
                                                                <li>Wasilisha karatasi ya malipo na fedha taslimu kwa keshia wa benki ili kukamilisha muamala.</li>
                                                            </ol>
                                                        </div>

                                                        <!-- NMB Wakala -->
                                                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                                            <h4 class="font-extrabold text-blue-950 text-[11px] uppercase tracking-wider">Kupitia NMB Wakala (NMB Agent)</h4>
                                                            <p class="text-slate-500 text-[10px] font-semibold leading-relaxed mb-1">
                                                                Hakikisha wakala anatumia mfumo sahihi wa NMB Bills Payment na anakupatia risiti rasmi ya benki:
                                                            </p>
                                                            <ol class="list-decimal pl-4 space-y-1 text-slate-600 leading-normal">
                                                                <li>Mpatie wakala Namba ya Malipo (Control Number) inayoanza na <strong>SASXXXXXXXXXXX</strong>.</li>
                                                                <li>Mwambie mlipwaji ni <strong>SINGIDA TEACHERS COLLEGE</strong>.</li>
                                                                <li>Mpatie kiasi cha fedha taslimu (<strong>TZS 20,000/=</strong>).</li>
                                                                <li>Wakala atakamilisha malipo na kukupatia risiti rasmi iliyochapishwa na benki.</li>
                                                            </ol>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- Alert / Note -->
                                                <div class="p-3 bg-blue-50 rounded-2xl border border-blue-100 text-blue-900 text-[10.5px] leading-relaxed">
                                                    💡 <strong>KUMBUKA:</strong> Baada ya kukamilisha malipo, mfumo utatambua malipo yako kiotomatiki bila kuhitaji kupakia risiti. Bofya kitufe cha "Endelea na Usajili" ili kurudi kwenye wizard na kuendelea na udahili.
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Sidebar Helpful Links & Contact -->
                <div class="space-y-6">
                    
                    <!-- Admissions Desk Info -->
                    <div class="bg-white rounded-3xl p-6 shadow-lg border border-slate-200 space-y-4 text-left">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="text-lg">📞</span> Helpdesk Support
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Kama unahitaji msaada wa haraka au una changamoto yoyote, tafadhali wasiliana nasi kupitia simu au barua pepe.
                        </p>
                        <div class="space-y-2.5 pt-2">
                            @php
                                $phone = \App\Models\Setting::get('contact_phone', '+255 22 266 8820');
                                $whatsapp = \App\Models\Setting::get('contact_whatsapp', '+255754000111');
                                $whatsappClean = preg_replace('/[^0-9]/', '', $whatsapp);
                                $email = \App\Models\Setting::get('contact_email', 'admissions@supa.ac.tz');
                            @endphp
                            <a href="tel:{{ $phone }}" class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-blue-50/50 border border-slate-100 transition-all group">
                                <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 group-hover:scale-110 transition-transform">📞</span>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-extrabold uppercase">Piga Simu / Call</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $phone }}</span>
                                </div>
                            </a>
                            @if($whatsapp)
                            <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-emerald-50/50 border border-slate-100 transition-all group">
                                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0 group-hover:scale-110 transition-transform">💬</span>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-extrabold uppercase">WhatsApp Chat</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $whatsapp }}</span>
                                </div>
                            </a>
                            @endif
                            <a href="mailto:{{ $email }}" class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-amber-50/50 border border-slate-100 transition-all group">
                                <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xs shrink-0 group-hover:scale-110 transition-transform">✉️</span>
                                <div class="max-w-[180px]">
                                    <span class="block text-[9px] text-slate-400 font-extrabold uppercase">Barua Pepe / Email</span>
                                    <span class="text-xs font-bold text-slate-800 truncate block">{{ $email }}</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Downloads -->
                    <div class="bg-white rounded-3xl p-6 shadow-lg border border-slate-200 space-y-4 text-left">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="text-lg">📥</span> Quick Downloads
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Pakua miongozo muhimu ya ada na taratibu za udahili kusaidia ukamilishaji wa maombi yako.
                        </p>
                        
                        <div class="space-y-3 pt-2">
                            <!-- Item 1: Admission Steps Guide -->
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <div class="flex items-start gap-2.5">
                                    <span class="text-lg shrink-0">📄</span>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 leading-tight">Mwongozo wa Udahili</h4>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-semibold">Admission Steps (PDF)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 pt-1">
                                    <a href="{{ route('public.admission-steps-guide') }}" target="_blank" class="flex-1 px-3 py-1.5 rounded-xl bg-white border border-slate-300 hover:border-slate-400 text-[10px] font-bold text-slate-700 hover:bg-slate-50 transition-all text-center">View</a>
                                    <a href="{{ route('public.admission-steps-guide') }}?download=1" target="_blank" class="flex-1 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-[10px] font-bold text-white transition-all text-center">Download</a>
                                </div>
                            </div>

                            <!-- Item 2: Payment Guideline -->
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                                <div class="flex items-start gap-2.5">
                                    <span class="text-lg shrink-0">💳</span>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 leading-tight">Mwongozo wa Malipo</h4>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-semibold">Payment Instructions (PDF)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 pt-1">
                                    <a href="{{ route('public.payment-guideline') }}" target="_blank" class="flex-1 px-3 py-1.5 rounded-xl bg-white border border-slate-300 hover:border-slate-400 text-[10px] font-bold text-slate-700 hover:bg-slate-50 transition-all text-center">View</a>
                                    <a href="{{ route('public.payment-guideline') }}?download=1" target="_blank" class="flex-1 px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-[10px] font-bold text-white transition-all text-center">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

</x-public-layout>
