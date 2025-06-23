<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchSuggestions(Request $request)
    {
        $keyword = $request->query('query');

        $suggestions = Article::where('title', 'like', '%' . $keyword . '%')
            ->limit(10)
            ->pluck('title');

        return response()->json($suggestions);
    }

    public function searchSuggestionsWithImages(Request $request)
    {
        $keyword = $request->query('query');

        $suggestions = Article::where('title', 'like', '%' . $keyword . '%')
            ->limit(10)
            ->get(['title', 'image', 'slug']);

        $result = $suggestions->map(function ($article) {
            return [
                'title' => $article->title,
                'slug' => $article->slug,
                'image_url' => $article->image ? asset('storage/uploads/images/article/' . $article->image) : null,
            ];
        });

        return response()->json($result);
    }
}
