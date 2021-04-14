<?php

namespace App\Http\Middleware;

use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockRecipe;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $recipeId = $request->route('recipeId') ?? -1;
        $stockId = $request->route('stockId') ?? -1;

        $recipe = StockRecipe::find($recipeId);
        if ($recipe == null){
            return response()->json(['message' => 'Recipe not found'], 404);
        }
        if ($recipe->stock_id == $stockId){
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);

    }
}
