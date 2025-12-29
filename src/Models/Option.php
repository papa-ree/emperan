<?php

namespace Paparee\BaleEmperan\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
}
