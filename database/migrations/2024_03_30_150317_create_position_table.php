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
        // Create the position table
        Schema::create('position', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('classification', ['teaching','teaching-related', 'non-teaching']);
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop the position table
        Schema::dropIfExists('position');
    }
};
