<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImage\StoreRequest;
use App\Http\Resources\PostImage\PostImageResource;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $newImage = $request->validated();
        $path = Storage::disk('public')->put('/images', $newImage['image']);
        $postImage = PostImage::create([
            'path' => $path,
            'user_id' => auth()->id(),
        ]);
        return new PostImageResource($postImage);
    }

    /**
     * Display the specified resource.
     */
    public function show(PostImage $postImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostImage $postImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostImage $postImage)
    {
        //
    }
}
