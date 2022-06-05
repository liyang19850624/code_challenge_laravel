<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image_url'
    ];

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            $product->tags()->detach();
        });
    }
}
