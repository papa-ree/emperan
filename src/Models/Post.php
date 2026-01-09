<?php

namespace Bale\Emperan\Models;

use Bale\Emperan\Traits\HasSeoMeta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasUuids;
    use HasSeoMeta;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d F Y'),
        );
    }

    /**
     * Generate excerpt from EditorJS content.
     */
    public function excerpt($limit = 160)
    {
        $content = $this->content ?? 'Belum ada konten';

        // Jika null → balikan string kosong
        if (!$content) {
            return '';
        }

        // Jika sudah string → langsung strip_tags
        if (is_string($content)) {
            return Str::limit(strip_tags($content), $limit);
        }

        // Jika array EditorJS → ekstrak blok text
        if (is_array($content) && isset($content['blocks'])) {
            $text = collect($content['blocks'])
                ->map(function ($block) {
                    // Hanya ambil text dari block yang punya "text"
                    return $block['data']['text'] ?? '';
                })
                ->implode(' '); // gabungkan semua teks
        } else {
            // fallback, kalau struktur tidak dikenal
            $text = json_encode($content);
        }

        // Hapus tag HTML, batasi panjang
        return Str::limit(strip_tags($text), $limit);
    }

}
