<?php

use Leaf\Schema;
use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionItems extends Database
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (!static::$capsule::schema()->hasTable('transaction_items')) :
            static::$capsule::schema()->create('transaction_items', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('transaction_id');
                $table->uuid('product_id');
                $table->integer('quantity');
                $table->decimal('price');
                $table->timestamps();
                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            });
        endif;

        // you can now build your migrations with schemas.
        // see: https://leafphp.dev/docs/mvc/schema.html
        // Schema::build('transaction_items');
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        static::$capsule::schema()->dropIfExists('transaction_items');
    }
}
