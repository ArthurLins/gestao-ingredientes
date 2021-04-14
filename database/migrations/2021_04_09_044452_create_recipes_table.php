<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_recipes', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable(false);
            $table->text("description");
            $table->unsignedBigInteger("stock_id")->nullable(false);
            $table->timestamps();

            $table->foreign("stock_id")
                ->references("id")
                ->on("stocks")
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
        Schema::dropIfExists('stock_recipes');
    }
}
