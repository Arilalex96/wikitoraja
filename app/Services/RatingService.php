<?php

namespace App\Services;

use App\Models\Rating;

class RatingService {

    //edit rating by contributor
    public function editRating($article_id, $rating_value){
        $rating = Rating::updateOrCreate(
            ['user_id' => auth()->user()->id, 'article_id'=> $article_id],
            ['value' => $rating_value]
        );
        
        return $rating;
    }

    public function calculateRatingThenUpdateRatingTemp(Rating $rating){
        $article = $rating->article;
        $ratings = $article->ratings;
        $sum = 0;
        foreach($ratings as $rating){
            $sum += $rating->value;
        }
        $result = $sum / $ratings->count();
        $article->rating_temp = $result;
        $article->save();
    }
}