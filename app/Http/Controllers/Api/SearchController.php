<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecentSearch;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CategoryResource;
use App\Models\Category; 

class SearchController extends Controller
{
    // Method to add a search term
    public function addRecentSearch(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate(['term' => 'required|string|max:255']);

        $user->recentSearches()->orderBy('created_at', 'desc')->skip(9)->delete();

        $search = RecentSearch::create([
            'user_id' => $user->id,
            'term' => $validatedData['term'],
        ]);

        return response()->json(['message' => 'Search term saved', 'search' => $search], 201);
    }

    // Method to get recent searches for the logged-in user
    public function getRecentSearches(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $recentSearches = $user
        ->recentSearches()
        ->orderByDesc('created_at') 
        ->limit(10) 
        ->pluck('term');

        return response()->json($recentSearches);
    }

    public function getPopularCategories(Request $request)
    {
        $popularCategories = Category::withCount('restaurants')
            ->orderBy('foods_count', 'desc')
            ->take($request->input('limit', 5))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $popularCategories,
        ]);

         if ($popularCategories->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No popular categories found',
            ], 404);
        }
    }
}
