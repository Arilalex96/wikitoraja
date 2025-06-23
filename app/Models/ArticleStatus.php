<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model
{
    protected $table = 'articles_status';

    protected $fillable = [
        'article_id',
        'value',
        'user_id',
    ];
}
