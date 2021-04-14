<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockRecipe;
use App\Models\StockRecipeItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RecipeItemsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create(Request $request, $recipeId){
        $this->validate($request, [
            //security issue
            'item_id' => 'required|exists:App\Models\StockItem,id',
            'quantity' => 'required|numeric',
        ]);

        $itemId = $request->input("item_id");
        $quantity = $request->input("quantity");

        $newRecipeItem = new StockRecipeItem();
        $newRecipeItem->stock_recipe_id = $recipeId;
        $newRecipeItem->stock_item_id = $itemId;
        $newRecipeItem->quantity = $quantity;
        $newRecipeItem->saveOrFail();

        return response()->json(['message' => 'Recipe item created'], 201);
    }

    public  function edit(Request $request, $recipeItemId){
        $this->validate($request, [
            //security issue
            'item_id' => 'required|exists:App\Models\StockItem,id',
            'quantity' => 'required|numeric',
        ]);

        $recipeItem = StockRecipeItem::findOrFail($recipeItemId);
        $recipeItem->quantity =  $request->input("quantity", $recipeItem->quantity);
        $recipeItem->update();

        return response()->json(['message' => 'Recipe item updated'], 200);
    }

    public function delete($recipeItemId){
        $recipeItem = StockRecipeItem::findOrFail($recipeItemId);
        $recipeItem->delete();
        return response()->json(['message' => 'Recipe item deleted'], 200);
    }

    public function all($recipeId){
        return StockRecipe::find($recipeId)->items() ?? [];
    }

    //
}
