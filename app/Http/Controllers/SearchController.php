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
}
