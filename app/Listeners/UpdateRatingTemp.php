<?php

namespace App\Listeners;

use App\Events\ArticleRatingChanged;
use App\Services\RatingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRatingTemp
{
    public $rating_service;
    /**
     * Create the event listener.
     */
    public function __construct(RatingService $rating_service)
    {
        $this->rating_service = $rating_service;
    }

    /**
     * Handle the event.
     */
    public function handle(ArticleRatingChanged $event): void
    {
        $this->rating_service->calculateRatingThenUpdateRatingTemp($event->rating);
    }
}
