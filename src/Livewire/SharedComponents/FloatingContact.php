<?php

namespace Bale\Emperan\Livewire\SharedComponents;

use Bale\Emperan\Models\Section;
use Livewire\Component;
use Illuminate\Support\Str;

class FloatingContact extends Component
{
    public array $contact = [];
    public string $title = 'Hubungi kami';
    public array $socialLinks = [];
    public array $contactInfos = [];
    public bool $hasContact = false;

    public function mount(): void
    {
        $model = Section::whereSlug('floating-contact')->first();
        $this->contact = $model?->content ?? [];
        
        $this->parseData();
    }

    protected function parseData(): void
    {
        $socialPlatforms = config('landing-page.social-media', []);
        $items = $this->contact['items'] ?? [];
        $contact = !empty($items) ? $items[0] : [];
        $meta = $this->contact['meta'] ?? [];
        $order = $meta['order'] ?? [];
        
        $this->title = $meta['title'] ?? 'Hubungi kami';

        // Helper: ambil nilai pertama dari array atau string
        $val = fn($v) => is_array($v) ? ($v[0] ?? null) : $v;

        // Kumpulkan sosmed yang tersedia
        foreach ($socialPlatforms as $key => $platform) {
            $contactKey = "sm_{$key}";
            $url = $val($contact[$contactKey] ?? null);
            if ($url) {
                $this->socialLinks[$key] = array_merge($platform, ['url' => $url]);
            }
        }

        // Kumpulkan info kontak berdasarkan meta.order
        foreach ($order as $field) {
            $raw = $val($contact[$field] ?? null);
            if ($raw) {
                $this->contactInfos[$field] = $raw;
            }
        }

        $this->hasContact = !empty($this->socialLinks) || !empty($this->contactInfos);
    }

    public function render()
    {
        return view('emperan::livewire.shared-components.floating-contact');
    }
}
