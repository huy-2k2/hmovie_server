<?php

namespace App\Http\Controllers;

use App\Events\NotifiEvent;
use App\Events\CommentEvent;
use App\Models\Comment;
use App\Models\Notifi;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getAllByFilmId(Request $request)
    {
        $comments = Comment::where('film_id', $request->filmId)->orderBy('created_at', 'DESC')->get();
        return response()->json(Comment::filterComments($comments));
    }

    public function createComment(Request $request)
    {
        $request->validate(([
            'content' => 'required|string',
            'userId' => 'required',
            'filmId' => 'required',
        ]));

        $comment =  Comment::create([
            'content' => $request->content,
            'user_id' => $request->userId,
            'film_id' => $request->filmId,
            'comment_id' => $request->commentId ?? null
        ]);
        $comments = Comment::filterComments(Comment::where('film_id', $request->filmId)->orderBy('created_at', 'DESC')->get());
        if ($request->commentId) {

            $userId = Comment::find($request->commentId)->user_id;
            $userComment = User::find($request->userId);
            if ($userId != $request->userId) {
                $notifi = Notifi::create(['comment_id' => $comment->id]);
                $notifi['user'] = $notifi->comment->user;
                $notifi['film_id'] = $request->filmId;
                broadcast(new NotifiEvent(['userId' => $userId, 'message' => $userComment->name . ' đã trả lời bình luận của bạn', 'notifi' => $notifi]));
            }
        }
        broadcast(new CommentEvent(['filmId' => $request->filmId, 'comments' => $comments]));
        return response()->json($comments);
    }

    public function removeComment(Request $request)
    {
        $comment = Comment::find($request->commentId);
        $filmId = $comment->film_id;;
        $comment->delete();
        $comments = Comment::filterComments(Comment::where('film_id', $filmId)->orderBy('created_at', 'DESC')->get());
        return response()->json($comments);
    }
}
