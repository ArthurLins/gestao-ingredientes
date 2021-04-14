<?php


namespace App\Http\Controllers;


use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemsController extends Controller
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
            'quantity' => 'required|numeric',
            'unit' => ['required', Rule::in(StockItem::$itemUnits)]
        ]);
        $newItem = new StockItem();
        $newItem->name = $request->input('name');
        $newItem->description = $request->input('description');
        $newItem->quantity = $request->input('quantity');
        $newItem->unit = $request->input('unit');
        $newItem->stock_id = $stockId;
        $newItem->saveOrFail();
        return response()->json(['message' => 'Item created'], 201);
    }

    public function incoming(Request $request, $itemId){
        $this->validate($request, [
            'quantity' => 'required|numeric|min:0|not_in:0',
        ]);
        $quantity = $request->input('quantity');
        $item = StockItem::findOrFail($itemId);
        $item->quantity += $quantity;
        $item->update();
        return response()->json(['message' => 'Item quantity updated'], 200);
    }

    public function outgoing(Request $request, $itemId){
        $this->validate($request, [
            'quantity' => 'required|numeric|min:0|not_in:0',
        ]);
        $quantity = $request->input('quantity');
        $item = StockItem::findOrFail($itemId);

        if ($item->quantity < $quantity){
            return response()->json(['message' => 'Quantity not available'], 400);
        }

        $item->quantity -= $quantity;
        $item->update();
        return response()->json(['message' => 'Item quantity updated'], 200);
    }

    public function edit(Request $request, $itemId){
        $this->validate($request, [
            'name' => 'max:64',
            'description' => 'string|max:1000',
            'unit' => [Rule::in(StockItem::$itemUnits)]
        ]);
        $item  = StockItem::findOrFail($itemId);
        $item->name = $request->input('name', $item->name);
        $item->description = $request->input('description', $item->description);
        $item->unit = $request->input('unit', $item->unit);
        $item->update();
        return response()->json(['message' => 'Item updated'], 200);
    }

    public function delete($itemId){
        $item = StockItem::findOrFail($itemId);
        $item->delete();
        return response()->json(['message' => 'Item deleted'], 200);
    }

    public function infos($itemId){
        return StockItem::findOrFail($itemId);
    }

    public function all($stockId){
        return Stock::find($stockId)->items() ?? [];
    }


}
