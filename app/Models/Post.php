<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $guarded = false;

    protected $withCount = ['comments'];

    protected $with = ['image', 'likedUsers', 'repostedPost', 'user'];

    public function image():HasOne
    {
        return $this->hasOne(PostImage::class, 'post_id', 'id')->whereNotNull('post_id');
    }

    public function getImageUrlAttribute()
    {
        return isset($this->image)? $this->image->url: null;
    }

    public function getPublishedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function likedUsers():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'liked_posts', 'post_id', 'user_id');
    }

    public function repostedPost():BelongsTo
    {
        return $this->belongsTo(Post::class, 'reposted_id', 'id');
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
