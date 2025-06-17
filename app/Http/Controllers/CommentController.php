<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contributor\CreateCommentRequest;
use App\Http\Requests\Contributor\EditCommentRequest;
use App\Http\Resources\Contributor\CommentResource;
use App\Models\Comment;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\CommentService;

class CommentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:create comment', only: ['createBackend']),
            new Middleware('permission:edit comment', only: ['editBackend']),
            new Middleware('permission:delete comment', only: ['deleteBackend']),
        ];
    }

    public function createBackend(CommentService $comment_service, CreateCommentRequest $create_comment_request){
        $data = $create_comment_request->validated();
        $comment = $comment_service->createComment($data);
        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully!',
            'data' => new CommentResource($comment),
        ], $status = 201);
    }

    public function editBackend(CommentService $comment_service, $comment_id,EditCommentRequest $edit_comment_request){
        $comment = Comment::find($comment_id);
        if(!$comment){
            abort(404);
        }

        if($comment->user_id != auth()->user()?->id){
            abort(403);
        }

        $data = $edit_comment_request->validated();
        
        if(!$comment->article?->status->value){
            abort(403);
        }

        $comment = $comment_service->editComment($comment_id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Comment edited successfully!',
            'data' => new CommentResource($comment),
        ], $status = 200);
    }

    public function deleteBackend(CommentService $comment_service, $comment_id){
        $comment = Comment::find($comment_id);
        if(!$comment){
            abort(404);
        }

        if($comment->user_id != auth()->user()?->id){
            abort(403);
        }

        if(!$comment->article?->status->value){
            abort(403);
        }

        $status = $comment_service->deleteComment($comment_id);
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully!',
        ], $status = 200);
    }
}
