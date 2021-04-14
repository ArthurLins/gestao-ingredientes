<?php

namespace App\Http\Middleware;

use App\Models\Stock;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockValidator
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
        $id = $request->route('stockId') ?? -1;
        $stock = Stock::find($id);
        if ($stock == null){
            return response()->json(['message' => 'Stock not found'], 404);
        }
        if ($stock->owner_id == Auth::id()){
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 401);

    }
}
