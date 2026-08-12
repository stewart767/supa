<x-public-layout title="Careers Portal - Job Opportunities">
    <!-- Hero Banner section -->
    <div class="w-full text-white py-8 px-4 border-b border-slate-200 relative overflow-hidden bg-cover bg-center bg-no-repeat bg-gradient-to-r from-slate-950 via-blue-950 to-slate-900"
         @if(\App\Models\Setting::get('banner_careers')) style="background-image: linear-gradient(to right, rgba(2, 6, 23, 0.95), rgba(30, 58, 138, 0.85)), url('{{ asset('storage/' . \App\Models\Setting::get('banner_careers')) }}');" @endif>
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="max-w-7xl mx-auto space-y-2 text-center relative z-10">
            <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[10px] font-extrabold uppercase tracking-wider">
                Work at SUPA / OUT University
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-none">Find Your Next Career Milestone</h1>
            <p class="text-slate-300 text-xs max-w-lg mx-auto">Explore premium academic roles, administrative opportunities, and technical positions across all campuses.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border-2 border-emerald-500/20 text-emerald-600 rounded-2xl text-xs font-bold shadow-md">
                {{ session('success') }}
            </div>
        @endif



        <!-- Featured / Available Vacancies Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($vacancies as $vac)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-3.5 hover:shadow-lg hover:border-amber-500/50 transition-all flex flex-col justify-between space-y-3 card-hover-effect">
                    <div class="space-y-2.5">
                        <div class="flex justify-between items-center">
                            <span class="px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[8px] font-extrabold uppercase tracking-wide">
                                {{ $vac->employment_type }}
                            </span>
                            <span class="text-[8px] text-slate-400 font-bold">Ref: {{ $vac->vacancy_number }}</span>
                        </div>
                        
                        <div class="space-y-0.5">
                            <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm leading-snug line-clamp-1" title="{{ $vac->job_title }}">{{ $vac->job_title }}</h3>
                            <p class="text-[10px] text-slate-500 font-semibold leading-tight line-clamp-1" title="{{ $vac->designation->name ?? 'N/A' }}">{{ $vac->designation->name ?? 'N/A' }}</p>
                        </div>

                        <div class="text-[9px] text-slate-500 font-semibold space-y-1 pt-1.5 border-t border-slate-100 flex items-center justify-between gap-1 flex-wrap">
                            <span class="truncate">📍 {{ $vac->campus->name ?? $vac->location }}</span>
                            <span class="text-red-500 shrink-0 font-bold">📅 {{ $vac->application_deadline->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <a href="{{ route('public.careers.show', $vac->vacancy_number) }}" class="gradient-btn w-full text-center py-1.5 rounded-xl text-white font-extrabold text-[9px] shadow-sm block">
                            View Details &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-500 space-y-3">
                    <span class="text-4xl block">🔍</span>
                    <h3 class="font-bold text-sm">No active job vacancies matched your search.</h3>
                    <p class="text-[11px] text-slate-500">Please refine your filters or check back later.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4 border-t">
            {{ $vacancies->links() }}
        </div>
    </div>
</x-public-layout>
