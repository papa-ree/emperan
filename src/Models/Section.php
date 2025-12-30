<?php

namespace Bale\Emperan\Models;

use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Section extends Model implements CanVisit
{
    use HasUuids;
    use HasVisits;

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
}
