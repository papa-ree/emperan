<?php

namespace Paparee\BaleEmperan\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Navigation extends Model
{
    use HasUuids;

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
            get: fn(string $value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    public function children()
    {
        return $this->hasMany(Navigation::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

}
