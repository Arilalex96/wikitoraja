<?php

namespace App\Services;
use App\Models\Article;
use App\Models\ArticleStatus;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\Reference;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleService {
    public function index() {
        $user = auth()->user();
        if($user->hasRole('validator')){
            return Article::all();
        }else if($user->hasRole('contributor')){
            return Article::where('user_id', $user->id)->get();
        }
    }

    public function getForEditView($id) {
        $article = Article::select([
            "id",
            "title",
            "category_id",
            "content",
            "image",
        ])->where('id', $id)->get();

        if($article->count() == 0){
            return null;
        }

        $article = collect($article->first());
        
        $tags = Article::find($id)->tags->select('name');
        $arr = [];
        foreach($tags as $t){
            array_push($arr, $t['name']);
        }
        $tags = implode(",", $arr);

        $article->put('tags', $tags);

        $article->put('references', Article::find($id)->references->select('link'));

        return $article;
    }

    public function create(array $data) {
        $image_filename = $this->storeFile($data['image']);
        $article = Article::create([
            'title' => $data['title'],
            'category_id' => $data['category'],
            'content' => $data['content'],
            'tags' => explode(",", $data['tags']),
            'user_id' => auth()->user()->id,
            'image' => $image_filename,
        ]);

        $references = explode(",", $data['references']);
        foreach($references as $r){
            Reference::create([
                'link' => $r,
                'article_id' => $article->id,
            ]);
        }

        return $article;
    }

    public function edit($article_id, array $data) {
        if($data['content'] ?? null){
            $data['content'] = str_replace("\r\n", "\n", $data['content']);
        }
        
        $article = Article::find($article_id);
        if($data['image'] ?? null){
            $new_filename = $this->storeFile($data['image']);
            $this->deleteFile($article->image);
            $data['image'] = $new_filename;
        }
        
        if(array_key_exists('tags', $data)){
            if(is_null($data['tags'])){
                $data['tags'] = [];
            }else{
                $tags = explode(",", $data['tags']);
                $data['tags'] = $tags;
            }
        }
        
        $article->update($data);

        //update references
        if($data['references'] ?? null){
            $article->references()->delete();
            $references = explode(",", $data['references']);
            foreach($references as $r){
                Reference::create([
                    'link' => $r,
                    'article_id' => $article->id,
                ]);
            }
        }
        return $article;
    }
    
    public function delete($id) {
        Article::find($id)->delete();
    }

    public function storeFile($file){
        $directory = 'public/uploads/images/article';
        $filename = uniqid().".".$file->getClientOriginalExtension();
        Storage::putFileAs($directory, $file, $filename);
        return $filename;
    }

    public function deleteFile($filename){
        if(!$filename){
            return false;
        }

        $directory = 'public/uploads/images/article';
        if(Storage::exists($directory.$filename)){
            Storage::delete($directory.$filename);
        }
    }

    public function editStatus($article_id, $value){
        ArticleStatus::updateOrCreate(
            ['user_id' => auth()->user()->id, 'article_id'=> $article_id],
            ['value' => $value]
        );

        return Article::find($article_id);
    }

    /*Homepage region*/
    public function indexWithPagination($page, $sort){
        $paginator = DB::table('articles')
            ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
            ->leftJoin('articles_status', 'articles_status.article_id', '=', 'articles.id')
            ->where('articles_status.value', true)
            ->select(
                'articles.id as id',
                'articles.title as title',
                DB::raw("SUBSTRING_INDEX(content,' ',20) content"),
                'categories.name as category',
                'articles.rating_temp as rating',
                'articles.image as image',
                'articles.slug as slug',
                'articles.created_at as created_at',
            )
            ->orderBy('articles.created_at', $sort)
            ->paginate(10,['*'], 'page', $page);

        if($page > $paginator->lastPage()){
            return false;
        }

        $articles = $paginator->items();
        foreach($articles as $a){
            $a->created_at = Carbon::parse($a->created_at)->format('j F Y');
            $a->link = route('single_article.view', ['article_slug'=> $a->slug]);
        }

        return [
            'articles' => $articles,
            'total_page' => $paginator->lastPage(),
        ];
    }

    public function searchWithPagination($search, $page, $sort){
        $paginator = DB::table('articles')
            ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
            ->leftJoin('articles_status', 'articles_status.article_id', '=', 'articles.id')
            ->leftJoin('references', 'references.article_id', '=', 'articles.id')
            ->where('articles_status.value', true)
            ->where(function ($query) use($search) {
                $query->where('title', 'like', '%'.$search.'%')
                      ->orWhere('content', 'like', '%'.$search.'%')
                      ->orWhere('references.link', 'like', '%'.$search.'%');
            })
            ->select(
                'articles.id as id', 
                'articles.title as title',
                DB::raw("SUBSTRING_INDEX(content,' ',20) content"),
                'categories.name as category', 
                'articles.rating_temp as rating',
                'articles.image as image',
                'articles.slug as slug',
                'articles.created_at as created_at',
            )
            ->groupBy('articles.id')
            ->orderBy('articles.rating_temp', $sort)
            ->paginate(10,['*'], 'page', $page);

        $articles = $paginator->items();
        foreach($articles as $a){
            $a->created_at = Carbon::parse($a->created_at)->format('j F Y');
            $a->link = route('single_article.view', ['article_slug'=> $a->slug]);
        }

        return [
            'articles' => $articles,
            'total_page' => $paginator->lastPage(),
        ];
    }

    public function filterByCategoryWithPagination($category_id, $page, $sort){
        $paginator = DB::table('articles')
        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
        ->leftJoin('articles_status', 'articles_status.article_id', '=', 'articles.id')
        ->where('articles_status.value', true)
        ->where('category_id', '=', $category_id)
        ->select(
            'articles.id as id', 
            'articles.title as title',
            DB::raw("SUBSTRING_INDEX(content,' ',20) content"),
            'categories.name as category', 
            'articles_status.value as status', 
            'articles.rating_temp as rating',
            'articles.image as image',
            'articles.slug as slug',
            'articles.created_at as created_at',
        )
        ->orderBy('articles.created_at', $sort)
        ->paginate(10,['*'], 'page', $page);

        $articles = $paginator->items();
        foreach($articles as $a){
            $a->created_at = Carbon::parse($a->created_at)->format('j F Y');
            $a->link = route('single_article.view', ['article_slug'=> $a->slug]);
        }

        $category = Category::where('id', $category_id)
                            ->select('id', 'name')
                            ->first();

        return [
            'articles' => $articles,
            'total_page' => $paginator->lastPage(),
            'category' => $category,
        ];
    }

    /*End of Homepage region */

    
    public function getSingleApprovedArticle($article_slug){
        $article = DB::table('articles')
                        ->where('slug', $article_slug)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->leftJoin('articles_status', 'articles_status.article_id', '=', 'articles.id')
                        ->leftJoin('references', 'references.article_id', '=', 'articles.id')
                        ->leftJoin('users', 'users.id', '=', 'articles.user_id')
                        ->where('articles_status.value', true)
                        ->select(
                            DB::raw("SUBSTRING_INDEX(users.name,' ',3) user_fullname"),
                            'articles.id as id',
                            'articles.title as title',
                            'articles.content as content',
                            'categories.id as category_id',
                            'categories.name as category_name',
                            'articles.rating_temp as rating',
                            'articles.image as image',
                            'articles.slug as slug',
                            'articles.created_at as created_at',
                            'references.link as references'
                        )
                        ->first();
        if($article){
            $article->created_at = Carbon::parse($article->created_at)->format('j F Y');
            $article->tags = Article::find($article->id)->tags->select('name');
            $article->references = Article::find($article->id)->references->select('link');
            $comments = Comment::where('article_id', $article->id)->get();
            $article->comments = [];
            foreach($comments as $comment){
                $article->comments[] = $comment->getDataForSingleArticle();
            }

            if(auth()->user()?->hasRole('contributor')){
                $article->current_user_rating = Rating::where('article_id', $article->id)
                                                        ->where('user_id', auth()->user()->id)
                                                        ->select('value')
                                                        ->first()
                                                        ->value ?? 0;
            }
        }

        return $article;
    }

    public function getArticlePreview($article_slug){
        $article = DB::table('articles')
                        ->where('slug', $article_slug)
                        ->leftJoin('categories', 'categories.id', '=', 'articles.category_id')
                        ->leftJoin('references', 'references.article_id', '=', 'articles.id')
                        ->leftJoin('users', 'users.id', '=', 'articles.user_id')
                        ->select(
                            DB::raw("SUBSTRING_INDEX(users.name,' ',3) user_fullname"),
                            'articles.id as id',
                            'articles.title as title',
                            'articles.content as content',
                            'categories.name as category_name',
                            'articles.rating_temp as rating',
                            'articles.image as image',
                            'articles.slug as slug',
                            'articles.created_at as created_at',
                            'references.link as references'
                        )
                        ->first();
        if($article){
            $article->created_at = Carbon::parse($article->created_at)->format('j F Y');
            $article->tags = Article::find($article->id)->tags->select('name');
            $article->references = Article::find($article->id)->references->select('link');
        }

        return $article;
    }
}