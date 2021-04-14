<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string("name")
                ->nullable(false);
            $table->text("description");
            $table->double("quantity")
                ->nullable(false);
            $table->unsignedBigInteger("stock_id")
                ->nullable(false);
            $table->enum("unit", ["KG", "G", "L", "ML"])
                ->default("KG");
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
        Schema::dropIfExists('stock_items');
    }
}
