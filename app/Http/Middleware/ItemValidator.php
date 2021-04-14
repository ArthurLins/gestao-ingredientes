<?php

namespace App\Http\Middleware;

use App\Models\Stock;
use App\Models\StockItem;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemValidator
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
        $itemId = $request->route('itemId') ?? -1;
        $stockId = $request->route('stockId') ?? -1;

        $item = StockItem::find($itemId);
        if ($item == null){
            return response()->json(['message' => 'Item not found'], 404);
        }
        if ($item->stock_id == $stockId){
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 401);

    }
}
