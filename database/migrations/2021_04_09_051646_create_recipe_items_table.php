<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_recipe_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("stock_recipe_id")
                ->nullable(false);
            $table->unsignedBigInteger("stock_item_id")
                ->nullable(false);
            $table->integer("quantity")
                ->nullable(false);

            $table->foreign("stock_recipe_id")
                ->references("id")
                ->on("stock_recipes")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_recipe_items');
    }
}
