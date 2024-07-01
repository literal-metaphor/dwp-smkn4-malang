<?php

use Leaf\Schema;
use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateProducts extends Database
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (!static::$capsule::schema()->hasTable('products')) :
            static::$capsule::schema()->create('products', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('owner_id');
                $table->string('name');
                $table->string('description')->nullable();
                $table->decimal('price');
                $table->enum('category', ['food', 'drink', 'female_fashion', 'male_fashion', 'child_fashion', 'furniture']);
                $table->timestamps();
                $table->foreign('owner_id')->references('id')->on('shops')->onDelete('cascade')->onUpdate('cascade');
            });
        endif;

        // you can now build your migrations with schemas.
        // see: https://leafphp.dev/docs/mvc/schema.html
        // Schema::build('products');
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        static::$capsule::schema()->dropIfExists('products');
    }
}
