<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Events\NotifiEvent;
use App\Models\Comment;
use App\Models\Emoji;
use App\Models\Notifi;
use App\Models\User;
use Illuminate\Http\Request;

class EmojiController extends Controller
{
    public function createEmoji(Request $request)
    {
        $request->validate(([
            'commentId' => 'required',
            'userId' => 'required',
            'statusId' => 'required',
        ]));

        $isEmojiExisted = Emoji::where('user_id', $request->userId)->where('comment_id', $request->commentId)->get()->count() > 0;

        $emoji = Emoji::updateOrCreate(
            [
                'user_id' => $request->userId,
                'comment_id' => $request->commentId
            ],
            ['status_id' => $request->statusId]
        );
        $comment = Comment::find($request->commentId);

        if (!$isEmojiExisted) {
            $userLike = User::find($request->userId);
            if ($comment->user->id != $request->userId) {
                $notifi = Notifi::create(['emoji_id' => $emoji->id]);
                $notifi['comment_id'] = $notifi->emoji->comment_id;
                $notifi['user'] = $userLike;
                $notifi['film_id'] = $comment->film_id;
                broadcast(new NotifiEvent(['userId' => $comment->user->id, 'message' => $userLike->name . ' đã bày tỏ cảm xúc về bình luận của bạn', 'notifi' => $notifi]));
            }
        }


        $comments = Comment::filterComments(Comment::where('film_id', $comment->film_id)->orderBy('created_at', 'DESC')->get());
        broadcast(new CommentEvent(['filmId' => $comment->film_id, 'comments' => $comments]));
        return response()->json($comments);
    }
}
