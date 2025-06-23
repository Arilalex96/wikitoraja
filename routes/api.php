<?php

use Illuminate\Support\Facades\Route;

$ctrl = '\App\Http\Controllers';

Route::get('/search-suggestions', $ctrl . '\SearchController@searchSuggestions')
    ->name('search.suggestions');

Route::get('/search-suggestions-with-images', $ctrl . '\SearchController@searchSuggestionsWithImages')
    ->name('search.suggestions.with_images');

Route::middleware(['auth:web'])->group(function () use ($ctrl) {

    //Profile
    Route::patch('user/password', $ctrl . '\UserController@editPasswordBackend')
        ->name('user.edit_password.backend');

    Route::post('user/profile-photo', $ctrl . '\UserController@editProfilePhotoBackend')
        ->name('user.edit_profile_photo.backend');


    /* Admin route */
    // Manage Contributor
    Route::get('manage/contributor', $ctrl . '\UserController@IndexContributorJson')
        ->name('contributor.index.json');

    Route::patch('manage/contributor/{user_id}/activation', $ctrl . '\UserController@editUserActivation')
        ->name('contributor.edit_activation.backend');

    // Manage Validator
    Route::get('manage/validator/{user_id}', $ctrl . '\UserController@getValidator')
        ->name('validator.get.json');

    Route::get('manage/validator', $ctrl . '\UserController@IndexValidatorJson')
        ->name('validator.index.json');

    Route::patch('manage/validator/{user_id}', $ctrl . '\UserController@edit')
        ->name('validator.edit.backend');

    Route::post('manage/validator', $ctrl . '\UserController@createValidator')
        ->name('validator.create.backend');

    Route::delete('manage/validator/{user_id}', $ctrl . '\UserController@deleteValidator')
        ->name('validator.delete.backend');


    /* Article route */
    Route::get('manage/article', $ctrl . '\ArticleController@indexJson')
        ->name('article.index.json');

    /* Contributor route */
    Route::post('manage/article/create', $ctrl . '\ArticleController@createBackend')
        ->where('id', '[0-9]+')
        ->name('article.create.backend');

    Route::post('manage/article/{article_id}/edit', $ctrl . '\ArticleController@editBackend')
        ->where('id', '[0-9]+')
        ->name('article.edit.backend');

    Route::patch('manage/article/{article_id}/status', $ctrl . '\ArticleController@editStatusBackend')
        ->where('id', '[0-9]+')
        ->name('article_status.edit.backend');

    Route::delete('manage/article/{article_id}', $ctrl . '\ArticleController@deleteBackend')
        ->where('id', '[0-9]+')
        ->name('article.delete.backend');

    Route::patch('/article/{article_slug}/rating', $ctrl . '\ArticleController@editRatingBackend')
        ->name('article_rating.edit.backend');
    /* End of Article Route */

    /* Comment route */
    Route::post('comment', $ctrl . '\CommentController@createBackend')
        ->where('comment_id', '[0-9]+')
        ->name('comment.create.backend');

    Route::patch('comment/{comment_id}', $ctrl . '\CommentController@editBackend')
        ->where('comment_id', '[0-9]+')
        ->name('comment.edit.backend');

    Route::delete('comment/{comment_id}', $ctrl . '\CommentController@deleteBackend')
        ->where('comment_id', '[0-9]+')
        ->name('comment.delete.backend');
    /* End of Comment Route */
});
