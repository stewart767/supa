<x-public-layout title="Track Application Status - SUPA University">

    <section class="relative text-white py-20 text-center space-y-4 border-b border-slate-200 bg-cover bg-center bg-no-repeat bg-gradient-to-r from-slate-950 via-blue-950 to-slate-900"
             @if(\App\Models\Setting::get('banner_track')) style="background-image: linear-gradient(to right, rgba(2, 6, 23, 0.95), rgba(30, 58, 138, 0.85)), url('{{ asset('storage/' . \App\Models\Setting::get('banner_track')) }}');" @endif>
        <span class="px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-extrabold uppercase tracking-wider">
            Real-Time Verification
        </span>
        <h1 class="hero-title font-extrabold tracking-tight">Track Application Status</h1>
        <p class="body-text text-slate-300 max-w-2xl mx-auto">
            Enter your Application Number (e.g., SUPA/2026/00001) to view real-time verification and admission progress.
        </p>
    </section>

    <section class="py-20 bg-white" x-data="{ appNumber: '', result: null, error: null, loading: false }">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-2xl border border-slate-200 space-y-6">
                
                <form @submit.prevent="
                    loading = true; error = null; result = null;
                    axios.post('{{ url('/api/v1/public/track-application') }}', { application_number: appNumber })
                        .then(res => { result = res.data; loading = false; })
                        .catch(err => { error = err.response?.data?.message || 'Application not found. Check application number.'; loading = false; })
                " class="space-y-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Application Control Number</label>
                        <div class="relative">
                            <input type="text" x-model="appNumber" required placeholder="e.g. SUPA/2026/00001" 
                                   class="w-full px-5 py-4 rounded-2xl border border-slate-300 bg-slate-50 text-slate-900 font-extrabold text-base focus:ring-2 focus:ring-amber-500 outline-none">
                        </div>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full gradient-btn py-4 rounded-2xl text-white font-extrabold text-sm shadow-xl flex items-center justify-center gap-2">
                        <span x-show="!loading">Track Status Now</span>
                        <span x-show="loading">Searching Database...</span>
                    </button>
                </form>

                <!-- Error Alert -->
                <div x-show="error" class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 text-xs font-bold" x-cloak x-text="error"></div>

                <!-- Result Card -->
                <div x-show="result" class="p-6 rounded-2xl bg-gradient-to-br from-blue-900/10 to-indigo-900/10 border border-blue-200 space-y-4" x-cloak>
                    <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                        <span class="text-xs font-extrabold text-slate-500 uppercase">Application #</span>
                        <span class="text-base font-black text-blue-600" x-text="result?.application_number"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-500 block font-semibold">Chosen Programme</span>
                            <span class="font-bold text-slate-900 text-sm" x-text="result?.programme"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block font-semibold">Admission Category</span>
                            <span class="font-bold text-slate-900 text-sm" x-text="result?.admission_category"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block font-semibold">Review Status</span>
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase mt-1"
                                  :class="{ 'bg-amber-100 text-amber-800': result?.status === 'Pending Payment' || result?.status === 'Under Review', 'bg-emerald-100 text-emerald-800': result?.status === 'Approved', 'bg-red-100 text-red-800': result?.status === 'Rejected' }"
                                  x-text="result?.status"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 block font-semibold">Fee Payment</span>
                            <span class="font-bold uppercase text-slate-900 text-sm" x-text="result?.payment_status"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-public-layout>
