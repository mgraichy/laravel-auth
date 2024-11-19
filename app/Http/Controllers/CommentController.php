<?php

namespace App\Http\Controllers;

use App\Models\Comment;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getComment(Request $request, Comment $comment)
    {
        $id = intval($request->query('id'));
        $comments = $comment->where('video_id', $id)->get();

        $finalComments = [];
        foreach ($comments as $comment)
        {
            $finalComments[] = [
                'name'    => $comment->name,
                'comment' => $comment->comment,
                'picture' => $comment->picture,
                'date' => $comment->created_at,
            ];
        }

        return $finalComments;
    }
}
