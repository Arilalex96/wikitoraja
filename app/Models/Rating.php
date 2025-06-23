<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'value',
        'article_id',
        'user_id',
    ];

    public function article(): BelongsTo{
        return $this->belongsTo(Article::class);
    }
}
