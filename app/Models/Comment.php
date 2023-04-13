<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $guarded = false;
    
    protected $with = ['user', 'parent'];
    
    public function getPublishedAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parent():BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }
}
