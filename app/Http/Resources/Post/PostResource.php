<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => $this->imageUrl,
            'published' => $this->published,
            'is_liked' => $this->is_liked,
            'likes_count' => $this->likes_count,
            'is_reposted' => $this->is_reposted,
            'reposted_count' => $this->reposted_count,
            'reposted_post' => new RepostedPostResource($this->repostedPost),
            'comments_count' => $this->comments_count,
            'author' => new UserResource($this->user),
        ];
    }
}
