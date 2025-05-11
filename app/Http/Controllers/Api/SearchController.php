<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecentSearch;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function storeRecentSearch(Request $request)
    {
        $validated = $request->validate(["term" => "required|string|max:255"]);
        
        $user = Auth::user();
        RecentSearch::firstOrCreate(
            ["user_id" => $user->id, "term" => $validated["term"]],
            ["updated_at" => now()]
        );
        
        $user->recentSearches()->orderByDesc("updated_at")->skip(10)->take(PHP_INT_MAX)->delete();

        return response()->json(["message" => "Search term saved"], 201);
    }

    public function getRecentSearches(Request $request)
    {
        $recentSearches = Auth::user()
        ->recentSearches()
        ->orderByDesc("updated_at")
        ->limit(10)
        ->pluck("term");
        return response()->json($recentSearches);
    }

    public function getPopularCategories(Request $request)
    {
        $popular = [{{objects}}];
        return response()->json($popular);
    }
}
