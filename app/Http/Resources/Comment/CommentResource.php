<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (isset($this->parent->user)) {
            $answer_for_user = $this->parent->user->name;
        } else {
            $answer_for_user = null;
        }
        
        return [
            'id' => $this->id,
            'body' => $this->body,
            'published' => $this->published,
            'user' => $this->user->name,
            'answer_for_user' => $answer_for_user,
        ];
    }
}
