<?php
namespace App\Actions\PostActions;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class GetCountRepostedPostsActions
{
    public function __invoke(Collection $posts):Collection
    {
        $myPostIds = $posts->pluck('id')->toArray();
        $repostedPosts = Post::whereIn('reposted_id', $myPostIds)->whereNotNull('reposted_id')->get('reposted_id')->pluck('reposted_id')->toArray();
        foreach ($posts as $post) {
            if (in_array($post->id, $repostedPosts)) {
                $post->is_reposted = true;
                $post->reposted_count = Post::where('reposted_id', '=', $post->id)->get('id')->count();
            } else {
                $post->is_reposted = false;
                $post->reposted_count = 0;
            }
        }
        return $posts;
    }
}
