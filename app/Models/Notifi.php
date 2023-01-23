<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifi extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'emoji_id',
        'created_at',
        'updated_at',
        'readed_at',
        'updated_at',
        'deleted_at'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function emoji()
    {
        return $this->belongsTo(Emoji::class);
    }
}
