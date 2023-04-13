<?php
namespace App\Actions\PostActions;

use App\Models\LikedPost;
use Illuminate\Database\Eloquent\Collection;

class GetLikedPostsActions
{
    public function __invoke(Collection $posts):Collection
    {
        $likedPosts = LikedPost::where('user_id', '=', auth()->id())->get('post_id')->pluck('post_id')->toArray();
        foreach ($posts as $post) {
            if (in_array($post->id, $likedPosts)) {
                $post->is_liked = true;
            } else {
                $post->is_liked = false;
            }
            $post->likes_count = $post->likedUsers()->count();
        }
        return $posts;
    }
}
