<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StockRecipeItem extends Model
{

    protected $fillable = ['stock_recipe_id', 'stock_item_id','quantity'];


    public $timestamps = false;
}
