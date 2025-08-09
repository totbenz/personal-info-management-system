<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_head_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_head_id');
            $table->string('leave_type');
            $table->year('year');
            $table->integer('available')->default(0);
            $table->integer('used')->default(0);
            $table->integer('ctos_earned')->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('school_head_id')->references('id')->on('personnels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_head_leaves');
    }
};
