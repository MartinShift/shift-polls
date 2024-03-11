<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // Modify the image_id column to allow null values
            $table->unsignedBigInteger('image_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            // If necessary, define the reverse migration (not null)
            // Note: This is optional and depends on your requirements
            $table->unsignedBigInteger('image_id')->nullable(false)->change();
        });
    }
};
