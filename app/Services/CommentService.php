<?php

namespace App\Services;
use App\Models\Comment;

class CommentService {
    public function createComment($data){
        $data['user_id'] = auth()->user()?->id;
        return Comment::create($data);
    }

    public function editComment($comment_id, $data){
        $data['user_id'] = auth()->user()?->id;
        $status = Comment::find($comment_id)->update($data);
        if($status)
            return Comment::find($comment_id);
        else
            return false;
    }

    public function deleteComment($comment_id){
        $status = Comment::find($comment_id)->delete();
        return $status;
    }
}