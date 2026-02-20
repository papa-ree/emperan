@props([
    'title' => 'Konten Hero Tidak Ditemukan',
    'message' => 'Silakan konfigurasi metadata section ini di panel admin CMS Anda agar tampil di halaman utama.'
])

<section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-slate-50 border-y border-slate-200">
    <div class="container mx-auto px-4 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">{{ $title }}</h2>
        <p class="text-slate-500 max-w-md mx-auto">{{ $message }}</p>
    </div>
</section>
