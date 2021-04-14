<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StockRecipe extends Model
{

    protected $fillable = ['name', 'description', 'stock_id'];

    public function items(){
        return $this->belongsTo(StockRecipeItem::class, 'id','stock_recipe_id')->get();
    }

}
