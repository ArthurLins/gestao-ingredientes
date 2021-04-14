<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockItem;
use App\Models\StockRecipe;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create(Request $request, $stockId){

        $this->validate($request, [
            'name' => 'required|max:64',
            'description' => 'required|string|max:1000',
        ]);

        $newRecipe = new StockRecipe();
        $newRecipe->name = $request->input('name');
        $newRecipe->description = $request->input('description');
        $newRecipe->stock_id = $stockId;
        $newRecipe->saveOrFail();
        return response()->json(['message' => 'Recipe created'], 201);
    }

    public  function edit(Request $request, $recipeId){
        $this->validate($request, [
            'name' => 'max:64',
            'description' => 'string|max:1000',
        ]);

        $recipe = StockRecipe::find($recipeId);
        $recipe->name = $request->input('name', $recipe->name);
        $recipe->description = $request->input('description', $recipe->description);
        $recipe->update();
        return response()->json(['message' => 'Recipe updated'], 201);
    }

    public function make($recipeId){

        $canMake = $this->canMake($recipeId);
        if (count($canMake) == 0){
            $items = StockRecipe::findOrFail($recipeId)->items();
            foreach ($items as $item){
                $stockItem = StockItem::find($item->stock_item_id);
                $stockItem->quantity -= $item->quantity;
                $stockItem->update();
            }
            return response()->json(['message' => 'Recipe make executed'], 200);
        } else {
            return response()->json([
                'message' => 'Missing recipe items',
                'items' => $canMake
            ], 400);
        }
    }

    public function undo($recipeId){
        $items = StockRecipe::findOrFail($recipeId)->items();
        foreach ($items as $item){
            $stockItem = StockItem::find($item->stock_item_id);
            $stockItem->quantity += $item->quantity;
            $stockItem->update();
        }
        return response()->json(['message' => 'Recipe undo executed'], 200);
    }

    public function canMake($recipeId){
        $missingItems = [];

        $items = StockRecipe::findOrFail($recipeId)->items();
        foreach ($items as $item){
            $stockItem = StockItem::find($item->stock_item_id);
            if ($item->quantity > $stockItem->quantity){
                array_push($missingItems, [
                    "id" => $stockItem->id,
                    "name" => $stockItem->name,
                    "missing_quantity" => ($item->quantity - $stockItem->quantity)
                ]);
            }
        }
        return $missingItems;
    }

    public function delete($recipeId){
        $recipe = StockRecipe::findOrFail($recipeId);
        $recipe->delete();
        return response()->json(['message' => 'Recipe deleted'], 200);
    }

    public function infos($recipeId){
        $recipe = StockRecipe::findOrFail($recipeId);
        $recipe["items"] = $recipe->items();
        return $recipe;
    }

    public function all($stockId){
        return Stock::find($stockId)->recipes() ?? [];
    }

    //
}
