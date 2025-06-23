<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function getDataForSingleArticle() : array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user_fullname' => $this->user->name,
            'created_at' => Carbon::parse($this->created_at)->format('j F Y'),
            'is_created_by_user' => ($this->user_id == auth()->user()?->id),
        ];
    }

    public function article(): BelongsTo{
        return $this->belongsTo(Article::class);
    }
}