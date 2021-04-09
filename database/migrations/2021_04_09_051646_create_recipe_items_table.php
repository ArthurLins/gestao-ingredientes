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
        Schema::create('recipe_items', function (Blueprint $table) {
            $table->unsignedBigInteger("recipe_id")
                ->nullable(false);
            $table->unsignedBigInteger("item_id")
                ->nullable(false);
            $table->integer("quantity")
                ->nullable(false);

            $table->foreign("recipe_id")
                ->references("id")
                ->on("recipes")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign("item_id")
                ->references("id")
                ->on("items")
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
        Schema::dropIfExists('recipe_items');
    }
}
