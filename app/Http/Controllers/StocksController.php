<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create(Request $request): JsonResponse
    {

        $this->validate($request, [
            'name' => 'required|max:64',
        ]);

        $newStock = new Stock();
        $newStock->name = $request->input('name');
        $newStock->owner_id = Auth::id();
        $newStock->saveOrFail();

        return response()->json(['message' => 'Stock created'], 201);
    }

    public function infos($stockId){
        return Stock::findOrFail($stockId);
    }

    public function delete($stockId): JsonResponse
    {
        Stock::findOrFail($stockId)->delete();
        return response()->json(['message' => 'Stock deleted'], 200);
    }

    public function all(Request $request) {
        return Auth::user()->stocks() ?? [];
    }

}
