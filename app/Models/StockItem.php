<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{

    public static $itemUnits = ["KG", "G", "L", "ML"];

    protected $fillable = ['name', 'description', 'quantity', 'stock_id', 'unit'];

}
