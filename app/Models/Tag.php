<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_name'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    protected static function booted()
    {
        static::deleting(function ($tag) {
            $tag->products()->detach();
        });
    }
}
