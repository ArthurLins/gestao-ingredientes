<?php

namespace App\Http\Middleware;

use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockRecipe;
use App\Models\StockRecipeItem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeItemValidator
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
        $recipeItemId = $request->route('recipeItemId') ?? -1;
        $recipeId = $request->route('recipeId') ?? -1;

        $recipeItem = StockRecipeItem::find($recipeItemId);
        if ($recipeItem == null){
            return response()->json(['message' => 'Recipe item not found'], 404);
        }
        if ($recipeItem->stock_recipe_id == $recipeId){
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);

    }
}
