<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;

class HomeController extends Controller
{
    public function indexArticleHomeView(
        ArticleService $article_service
    ){
        try {
            request()->validate([
                'page' => ['integer'],
                'sort' => ['string', 'in:asc,desc'],
                'category' => ['integer', 'exists:categories,id']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e){
            abort(404);
        }

        $page = request()->query('page') ?? null;
        $sort = request()->query('sort') ?? null;
        $search = request()->query('search') ?? null;
        $category = request()->query('category') ?? null;

        if(!$page && !$sort && !$category){
            $page = 1;
            $sort = 'desc';
        }
        
        $data = null;
        if($search){
            $data = $article_service->searchWithPagination($search, $page, $sort);
            if(!$data){
                abort(404);
            }
        }else if($category){
            $data = $article_service->filterByCategoryWithPagination($category, $page, $sort);
            $category = $data['category'];
            if(!$data){
                abort(404);
            }
        }else{
            $data = $article_service->indexWithPagination($page, $sort);
            if(!$data){
                abort(404);
            }
        }

        return view('all.home', [
            'articles'=> $data['articles'],
            'total_page' => $data['total_page'],
            'current_page' => $page,
            'sort' => $sort,
            'search' => $search,
            'category' => $category,
        ]);
    }
}
