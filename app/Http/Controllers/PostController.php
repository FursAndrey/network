<?php

namespace App\Http\Controllers;

use App\Actions\PostActions\GetCountRepostedPostsActions;
use App\Actions\PostActions\GetLikedPostsActions;
use App\Http\Requests\Comment\StoreRequest as CommentStoreRequest;
use App\Http\Requests\Post\RepostRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('user_id', '=', auth()->id())->latest()->get();
        
        $posts = (new GetLikedPostsActions)($posts);
        $posts = (new GetCountRepostedPostsActions)($posts);
        
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $imageId = $this->unsetImageId($data);
            $data = $this->preparePostDataForSave($data);
            $post = Post::create($data);
    
            $this->saveImageToPost($post, $imageId);
            PostImage::clearStorage();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }

        return new PostResource($post);
    }

    public function repost(RepostRequest $request, Post $post)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['reposted_id'] = $post->id;

        Post::create($data);

        return ['status' => true];
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function likeToggle(Post $post)
    {
        $res = auth()->user()->likedPosts()->toggle($post);
        $data['is_liked'] = count($res['attached']);
        $data['likes_count'] = $post->likedUsers()->count();
        return $data;
    }

    public function storeComment(CommentStoreRequest $request, Post $post)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['post_id'] = $post->id;
        Comment::create($data);

        return ['status' => true];
    }

    public function getComments(Post $post)
    {
        $comments = $post->comments()->latest()->get();
        return CommentResource::collection($comments);
    }

    protected function preparePostDataForSave(array $data):array
    {
        $data['user_id'] = auth()->id();
        unset($data['image_id']);
        return $data;
    }

    protected function unsetImageId(array $data):?int
    {
        $imageId = $data['image_id'];
        return $imageId;
    }

    protected function saveImageToPost($post, $imageId)
    {
        if (isset($imageId)) {
            $postImage = PostImage::find($imageId);
            $postImage->update([
                'post_id' =>$post->id,
            ]);
        }
    }
}
