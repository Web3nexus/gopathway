<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author_id',
        'is_published',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class , 'author_id');
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($post) {
            if (!$post->slug) {
                $post->slug = \Illuminate\Support\Str::slug($post->title);
            }
        });
    }
}