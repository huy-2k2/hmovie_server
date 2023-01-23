<?php

namespace App\Http\Controllers;

use App\Models\Notifi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class NotifiController extends Controller
{
    public function getAllByUserId(Request $request)
    {
        $request->validate(['userId' => 'required']);

        $notifis = Notifi::whereNull('deleted_at')->get();
        $results = [];
        foreach ($notifis as $notifi) {
            $key = $notifi->emoji_id ? 'emoji' : 'comment';
            if ($notifi->$key->user->id == $request->userId) {
                $notifi['user'] = $notifi->$key->user;
                if ($key == 'emoji') {
                    $notifi['comment_id'] = $notifi->$key->comment_id;
                    $notifi['film_id'] = $notifi->$key->comment->film_id;
                } else {
                    $notifi['film_id'] = $notifi->$key->film_id;
                }
                $results[] = $notifi;
            }
        }
        return response()->json($results);
    }

    public function markNotifiReaded(Request $request)
    {
        $request->validate(['notifiId' => 'required']);
        $timestamp = Carbon::now();
        Notifi::find($request->notifiId)->update(['readed_at' => $timestamp]);
        return response()->json($timestamp);
    }

    public function deleteNotifi(Request $request)
    {
        $request->validate(['notifiId' => 'required']);
        $timestamp = Carbon::now();
        Notifi::find($request->notifiId)->update(['deleted_at' => $timestamp]);
        return response()->json($timestamp);
    }
}
