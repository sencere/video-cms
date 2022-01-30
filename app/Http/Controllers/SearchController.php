<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Video;
use App\Http\Requests;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->q) {
            return redirect('/');
        }
        $channels = Channel::where('name', 'like', $request->q)->orWhere('description', 'like', $request->q)->get();
        $videos = Video::where('title', 'like', $request->q)->orWhere('description', 'like', $request->q)->get();

        return view('search.index', [
            'channels' => $channels,
            'videos' => $videos,
        ]);
    }
}
