<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->enum('type', ['elementary', 'secondary', 'vocational/trade', 'graduate', 'graduate studies']);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('school_name')->nullable();
            $table->string('degree_course')->nullable();
            $table->string('major')->nullable();
            $table->string('minor')->nullable();
            $table->integer('period_from')->nullable();
            $table->integer('period_to')->nullable();
            $table->string('highest_level_units')->nullable();
            $table->integer('year_graduated')->nullable();
            $table->string('scholarship_honors')->nullable();
            $table->timestamps();

            $table->foreign('personnel_id')
                ->references('id')
                ->on('personnels')
                ->onDelete('cascade');

            $table->index(['personnel_id', 'type', 'sort_order'], 'idx_education_entries_personnel_type_sort');
        });

        if (Schema::hasTable('educations')) {
            $now = Carbon::now();

            DB::table('educations')
                ->orderBy('id')
                ->chunkById(200, function ($rows) use ($now) {
                    $inserts = [];

                    foreach ($rows as $row) {
                        $inserts[] = [
                            'personnel_id' => $row->personnel_id,
                            'type' => $row->type,
                            'sort_order' => 0,
                            'school_name' => $row->school_name,
                            'degree_course' => $row->degree_course,
                            'major' => $row->major,
                            'minor' => $row->minor,
                            'period_from' => $row->period_from,
                            'period_to' => $row->period_to,
                            'highest_level_units' => $row->highest_level_units,
                            'year_graduated' => $row->year_graduated,
                            'scholarship_honors' => $row->scholarship_honors,
                            'created_at' => $row->created_at ?? $now,
                            'updated_at' => $row->updated_at ?? $now,
                        ];
                    }

                    if (!empty($inserts)) {
                        DB::table('education_entries')->insert($inserts);
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_entries');
    }
};
