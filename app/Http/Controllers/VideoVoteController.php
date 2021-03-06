<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\CreateVoteRequest;

class VideoVoteController extends Controller
{
    public function create(CreateVoteRequest $request, Video $video)
    {
        $this->authorize('vote', $video);

        if ($video->voteFromUser($request->user())->count() > 0) {
            $video->voteFromUser($request->user())->first()->delete();
        }

        $video->votes()->create([
            'type' => $request->type,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(null, 200);
    }

    public function remove(Request $request, Video $video)
    {
        $this->authorize('vote', $video);
        $video->voteFromUser($request->user())->first()->delete();

        return response()->json(null, 200);
    }

    public function show(Request $request, Video $video)
    {
        $response = [
            'up' => null,
            'down' => null,
            'can_vote' => $video->votesAllowed(),
            'user_vote' => null,
        ];

        if ($video->votesAllowed()) {
            $response['up'] = $video->upVotes()->count();
            $response['down'] = $video->downVotes()->count();
        }

        if ($request->user()) {
            $voteFromUser = $video->voteFromUser($request->user())->first();
            $response['user_vote'] = $voteFromUser ? $voteFromUser->type : null;
        }

        return response()->json([
            'data' => $response
        ], 200);
    }
}
