<?php

namespace Paparee\BaleEmperan\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasUuids;

    /**
     * Tentukan nama tabel.
     * Diasumsikan setiap tenant memiliki tabel 'posts'
     */
    protected $table = 'posts';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

}
