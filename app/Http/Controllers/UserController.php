<?php

namespace App\Http\Controllers;

use App\Actions\PostActions\GetCountRepostedPostsActions;
use App\Actions\PostActions\GetLikedPostsActions;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\Comment;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('id', auth()->id())->get();
        $followingIds = SubscriberFollowing::where('subscriber_id', '=', auth()->id())->get('following_id')->pluck('following_id')->toArray();
        foreach ($users as $user) {
            if (in_array($user->id, $followingIds)) {
                $user->is_followed = true;
            } else {
                $user->is_followed = false;
            }
        }
        return UserResource::collection($users);
        
        //ВАРИАНТ №2
        // $users = User::whereNot('users.id', auth()->id())->followMe()->get();
        // return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $posts = $user->posts()->latest()->get();
        $posts = (new GetLikedPostsActions)($posts);
        $posts = (new GetCountRepostedPostsActions)($posts);

        return PostResource::collection($posts);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function followingToggle(User $user)
    {
        $res = auth()->user()->followings()->toggle($user);
        $data['is_followed'] = count($res['attached']);
        return $data;
    }

    public function followingPosts()
    {
        $followingIds = auth()->user()->followings()->get()->pluck('id')->toArray();
        $likedPosts = LikedPost::where('user_id', '=', auth()->id())->get('post_id')->pluck('post_id')->toArray();
        $posts = Post::whereIn('user_id', $followingIds)->whereNotIn('id', $likedPosts)->latest()->get();
        $posts = (new GetLikedPostsActions)($posts);
        $posts = (new GetCountRepostedPostsActions)($posts);

        return PostResource::collection($posts);
    }

    public function userStat($userId)
    {
        $response = [];

        if (is_null($userId) || $userId == 'null') {
            $userId = auth()->id();
        }

        $myPosts = Post::where('user_id', '=', $userId)->get('id')->pluck('id')->toArray();
        $response['posts_count'] = count($myPosts);
        $response['likes_count'] = LikedPost::whereIn('post_id', $myPosts)->count();
        $response['comments_count'] = Comment::whereIn('post_id', $myPosts)->count();
        $response['followings_count'] = SubscriberFollowing::where('subscriber_id', '=', $userId)->count();
        $response['subscribers_count'] = SubscriberFollowing::where('following_id', '=', $userId)->count();
        $response['userId'] = $userId;

        return response()->json(['data' => $response]);
    }
}
