<?php

namespace Bale\Emperan\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    /**
     * Relasi ke Post melalui category_slug
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_slug', 'slug');
    }
}
