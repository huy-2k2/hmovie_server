<?php

namespace App\Http\Middleware;

use App\Models\Notifi;
use Closure;
use Illuminate\Http\Request;

class NotifiOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $notifi = Notifi::find($request->notifiId);
        if ($notifi->comment_id) {
            return $notifi->comment->parent->user->id == $request->userId ? $next($request) : false;
        } else {
            return $notifi->emoji->comment->user->id == $request->userId ? $next($request) : false;
        }
    }
}
