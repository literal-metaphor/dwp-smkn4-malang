<?php

use Leaf\Schema;
use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateReviewPhotos extends Database
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (!static::$capsule::schema()->hasTable('review_photos')) :
            static::$capsule::schema()->create('review_photos', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('review_id');
                $table->uuid('photo_id');
                $table->timestamps();
                $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('photo_id')->references('id')->on('files')->onDelete('cascade')->onUpdate('cascade');
            });
        endif;

        // you can now build your migrations with schemas.
        // see: https://leafphp.dev/docs/mvc/schema.html
        // Schema::build('review_photos');
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        static::$capsule::schema()->dropIfExists('review_photos');
    }
}
