<?php

use Illuminate\Support\Facades\Route;

$ctrl = '\App\Http\Controllers';

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('home', $ctrl . '\HomeController@indexArticleHomeView')
    ->name('home');

Route::get('contact', function () {
    return view('all.contact');
})->name('contact');

Route::get('about', function () {
    return view('all.about');
})->name('about');

Route::get('articles/{article_slug}', $ctrl . '\ArticleController@getPublicArticle')
    ->name('single_article.view');

//Auth
Route::get('login', function () {
    return view('all.login');
})->name('login');

Route::post('login', $ctrl . '\Auth\AuthenticatedSessionController@store')
    ->name('login.backend');

Route::post('logout', $ctrl . '\Auth\AuthenticatedSessionController@destroy')
    ->name('logout.backend');

Route::get('profile', $ctrl . '\UserController@profileView')
    ->name('profile');

//Registration
Route::get('contributor/register', function () {
    return view('contributor.register');
})->name('contributor.register.view');

Route::post('contributor/register', $ctrl . '\UserController@createContributor')
    ->name('contributor.register.backend');


Route::middleware(['auth:web'])->group(function () use ($ctrl) {

    /* Admin route */
    Route::get('manage/contributor', $ctrl . '\UserController@IndexContributorView')
        ->name('contributor.index.view');

    Route::get('manage/validator', $ctrl . '\UserController@IndexValidatorView')
        ->name('validator.index.view');


    /* Contributor & Validator route */
    //Manage Article:
    Route::get('manage/article', $ctrl . '\ArticleController@indexView')
        ->name('article.index.view');

    /* Validator route */
    //Article Preview
    Route::get('article/{article_slug}/preview', $ctrl . '\ArticleController@getArticlePreview')
        ->name('article_preview.view');


    /* Contributor route */
    //Manage Article:
    Route::get('manage/article/create', $ctrl . '\ArticleController@createView')
        ->where('id', '[0-9]+')
        ->name('article.create.view');

    Route::get('manage/article/{article_id}/edit', $ctrl . '\ArticleController@editView')
        ->where('article_id', '[0-9]+')
        ->name('article.edit.view');
});
