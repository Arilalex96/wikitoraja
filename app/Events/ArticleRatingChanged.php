<?php

namespace App\Events;

use App\Models\Rating;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleRatingChanged
{
    use Dispatchable, SerializesModels;
    
    public $rating;
    /**
     * Create a new event instance.
     */
    public function __construct(
        Rating $rating
    ){
        $this->rating = $rating;
    }
}
