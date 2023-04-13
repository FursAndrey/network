<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts():HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function followings():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscriber_followings', 'subscriber_id', 'following_id');
    }

    public function likedPosts():BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'liked_posts', 'user_id', 'post_id');
    }

    // public function scopeFollowMe($query)
    // {
    //     return $query->select('users.id', 'users.name', 'users.email', DB::raw('if (isnull(subscriber_followings.subscriber_id), false, true) as is_followed'))
    //         ->leftJoin('subscriber_followings', function ($join) {
    //             $join->on('users.id', '=', 'subscriber_followings.following_id')
    //                 ->where('subscriber_followings.subscriber_id', '=', auth()->id());
    //         });
    // }
}
