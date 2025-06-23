<?php

namespace App\Http\Controllers;

use App\Events\ArticleRatingChanged;
use App\Http\Requests\Contributor\CreateArticleRequest;
use App\Http\Requests\Contributor\EditArticleRatingRequest;
use App\Http\Requests\Contributor\EditArticleRequest;
use App\Http\Requests\Validator\EditArticleStatusRequest;
use App\Http\Resources\Contributor\ArticleSimplifiedResource;
use App\Http\Resources\Contributor\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use App\Services\RatingService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:index article', only: ['indexView', 'indexJson']),
            new Middleware('permission:create article', only: ['createView', 'createBackend']),
            new Middleware('permission:edit article', only: ['editView', 'editBackend']),
            new Middleware('permission:delete article', only: ['deleteBackend']),
            new Middleware('permission:approval article', only: ['editStatusBackend']),
            new Middleware('permission:edit article rating', only: ['editRatingBackend']),
            new Middleware('permission:get article preview', only: ['getArticlePreview']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function indexView()
    {
        $role = auth()->user()?->getRoleNames()->first();
        if($role == 'contributor'){
            $categories = Category::all();
            return view($role.'.article_index', ['categories' => $categories]);
        }
        return view($role.'.article_index');
    }

    public function indexJson(ArticleService $article_service)
    {
        $data = $article_service->index();
        return ArticleSimplifiedResource::collection($data);
    }

    public function createView()
    {
        $categories = Category::all();
        return view('contributor.article_create', ['categories' => $categories]);
    }

    public function createBackend(ArticleService $article_service, CreateArticleRequest $create_article_request)
    {
        $data = $create_article_request->validated();
        $article = $article_service->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully!',
            'data' => new ArticleResource($article),
        ], $status = 201);
    }

    public function editView(ArticleService $article_service, string $id)
    {
        $article = $article_service->getForEditView($id);
        if(!$article){
            return abort(404);
        }
        $categories = Category::all();
        $article = $article_service->getForEditView($id);

        return view('contributor.article_edit', ['article' => $article, 'categories' => $categories]);
    }

    public function editBackend(
        ArticleService $article_service, 
        EditArticleRequest $edit_article_request, 
        $article_id
    )
    {
        if(auth()->user()?->articles->where('id', $article_id)->count() == 0){
            abort(403);
        }

        $data = $edit_article_request->validated();
        $article = $article_service->edit($article_id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully!',
            'data' => new ArticleResource($article),
        ], $status = 200);
    }

    public function editStatusBackend(        
        ArticleService $article_service, 
        EditArticleStatusRequest $edit_article_status_request, 
        $article_id
    ){
        $data =  $edit_article_status_request->validated();
        $article = $article_service->editStatus($article_id, $data['status']);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully!',
            'data' => new ArticleSimplifiedResource($article),
        ], $status = 200);

    }

    public function deleteBackend(ArticleService $article_service, string $article_id)
    {
        if(auth()->user()->articles->where('id', $article_id)->count() == 0){
            abort(403);
        }

        $article_service->delete($article_id);

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully!',
        ], $status = 200);
    }

    //get single public (or approved) article
    public function getPublicArticle($article_slug, ArticleService $article_service){
        $article = $article_service->getSingleApprovedArticle($article_slug);
        if(!$article)
            abort(404);

        return view('all.article_single', ['article'=>$article]);
    }

    public function editRatingBackend(
        $article_slug, 
        EditArticleRatingRequest $edit_article_rating_request, 
        RatingService $rating_service
    ){
        $article = Article::leftJoin('articles_status', 'articles_status.article_id', '=', 'articles.id')
                        ->where('slug', $article_slug) 
                        ->where('articles_status.value', true)
                        ->select('articles.id as id')   
                        ->first();
        if(!$article){
            abort(404);
        }

        $data =  $edit_article_rating_request->validated();
        $rating = $rating_service->editRating($article->id, $data['rating']);
        event(new ArticleRatingChanged($rating));

        return response()->json([
            'success' => true,
            'message' => 'Article rating updated successfully!',
        ], $status = 200);
    }

    public function getArticlePreview(
        $article_slug,
        ArticleService $article_service
    ){
        $article = $article_service->getArticlePreview($article_slug);
        if(!$article)
            abort(404);

        return view('validator.article_preview', ['article'=>$article]);
    }
}