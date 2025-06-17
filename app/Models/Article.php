<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Tags\HasTags;

class Article extends Model
{
    use HasSlug;
    use HasTags;
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'content',
        'user_id',
        'tags',
        'image',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(ArticleStatus::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(Reference::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Reference::class);
    }
}
