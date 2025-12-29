<?php

namespace Bale\Emperan\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    /**
     * Automatically cast content JSON <-> array
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? json_decode($value, true) : [],
            set: fn($value) => json_encode($value)
        );
    }

    public function visit()
    {
        return visits($this);
    }
}
