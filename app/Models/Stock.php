<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{

    protected $fillable = ["name", "owner_id"];
    protected $hidden = ['owner_id'];

    public function items(){
        return $this->belongsTo(StockItem::class,"id", "stock_id")->get();
    }

    public function recipes(){
        return $this->belongsTo(StockRecipe::class, "id", "stock_id")->get();
    }

}
