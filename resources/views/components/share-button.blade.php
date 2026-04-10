@props(['url', 'title', 'text' => ''])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-3 no-print']) }}>
    <!-- Tombol Cetak -->
    <button onclick="window.print()" type="button"
        class="inline-flex items-center gap-2 cursor-pointer px-5 py-2.5 bg-gray-200 dark:bg-slate-800/60 hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-300 group/print font-semibold border border-transparent hover:border-gray-300 dark:hover:border-slate-600">
        <svg class="w-4 h-4 transition-transform group-hover/print:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
            </path>
        </svg>
        <span class="text-xs md:text-sm">Cetak</span>
    </button>

    <!-- Tombol Bagikan -->
    <div x-data="{
        copied: false,
        share() {
            const shareTitle = @js($title);
            const shareText = @js($text);
            const shareUrl = @js($url);
            
            const fullText = shareText ? `${shareTitle}\n\n${shareText}\n\nInfo selengkapnya: ${shareUrl}` : `${shareTitle}\n\n${shareUrl}`;

            if (navigator.share) {
                navigator.share({
                    title: shareTitle,
                    text: fullText,
                    url: shareUrl
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(fullText).then(() => {
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                });
            }
        }
    }">
        <button @click="share" type="button"
            class="inline-flex items-center gap-2 cursor-pointer px-5 py-2.5 bg-gray-200 dark:bg-slate-800/60 hover:bg-gray-300 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-300 group/share font-semibold border border-transparent hover:border-gray-300 dark:hover:border-slate-600">
            <div class="relative">
                <svg x-show="!copied" class="w-4 h-4 transition-transform group-hover/share:rotate-12" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                    </path>
                </svg>
                <svg x-show="copied" x-cloak class="w-4 h-4 text-green-500 animate-bounce" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span x-text="copied ? 'Tersalin!' : 'Bagikan'" class="text-xs md:text-sm"></span>
        </button>
    </div>
</div>

<style>
    @media print {
        /* Sembunyikan semua elemen kecuali area konten dan identitas situs */
        body > *:not(main):not(header):not(article) {
            display: none !important;
        }

        /* Bersihkan Header agar hanya menyisakan logo dan nama */
        header, nav, .topbar, #topbar {
            display: block !important;
            border: none !important;
            box-shadow: none !important;
            padding: 10px 0 !important;
            margin-bottom: 30px !important;
        }

        /* Sembunyikan navigasi menu, tombol cari, dsb */
        header *:not(.logo-container):not(.logo):not(.site-name):not(.site-name *):not(img) {
            display: none !important;
        }

        /* Pastikan Logo dan Nama tampil berdampingan */
        .logo-container, .logo, .site-name {
            display: inline-flex !important;
            visibility: visible !important;
            vertical-align: middle !important;
        }

        .no-print, button, footer, .sidebar, aside {
            display: none !important;
        }

        body {
            background: white !important;
            color: black !important;
            margin: 0 !important;
        }

        main, article, .editorjs-content {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>