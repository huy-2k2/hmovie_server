<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LDAP\Result;
use PhpParser\Node\Expr\FuncCall;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'user_id',
        'film_id',
        'comment_id',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class);
    }

    public function emojis()
    {
        return $this->hasMany(Emoji::class);
    }

    public function notifi()
    {
        return $this->hasOne(Notifi::class);
    }

    public static function filterComments($comments)
    {
        $result = [];
        foreach ($comments as $comment) {
            $comment['user'] = $comment->user;
            $comment['emojis'] = $comment->emojis;
            foreach ($comment['emojis'] as $emoji) {
                $emoji['user'] = $emoji->user;
                $emjo['status'] = $emoji->status;
                unset($emoji['status_id']);
                unset($emoji['user_id']);
            }
            unset($comment['user_id']);
            $result[] = $comment;
        }
        return $result;
    }

    use HasFactory;
}
