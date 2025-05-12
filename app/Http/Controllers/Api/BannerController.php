<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        return BannerResource::collection($banners);
    }

    public function show(Banner $banner)
    {
        return new BannerResource($banner);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'required|url',
            'target_url' => 'required|url',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer'
        ]);

        $banner = Banner::create($validated);
        return new BannerResource($banner);
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'image_url' => 'sometimes|url',
            'target_url' => 'sometimes|url',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer'
        ]);

        $banner->update($validated);
        return new BannerResource($banner);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return response()->noContent();
    }
}