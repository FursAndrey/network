<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;

    protected $table = 'post_images';
    protected $guarded = false;

    public function getUrlAttribute():string
    {
        return url('storage/' . $this->path);
    }

    public static function clearStorage():void
    {
        $images = self::where('user_id', '=', auth()->id())->whereNull('post_id')->get();
        foreach ($images as $image) {
            $image->delete();
            Storage::disk('public')->delete($image->path);
        }
    }
}
