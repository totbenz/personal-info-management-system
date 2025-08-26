<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('teacher_leaves')) {
            Schema::table('teacher_leaves', function (Blueprint $table) {
                if (!Schema::hasColumn('teacher_leaves', 'teacher_id')) {
                    $table->unsignedBigInteger('teacher_id')->after('id');
                }
                if (!Schema::hasColumn('teacher_leaves', 'leave_type')) {
                    $table->string('leave_type')->after('teacher_id');
                }
                if (!Schema::hasColumn('teacher_leaves', 'year')) {
                    $table->year('year')->after('leave_type');
                }
                if (!Schema::hasColumn('teacher_leaves', 'available')) {
                    $table->integer('available')->default(0)->after('year');
                }
                if (!Schema::hasColumn('teacher_leaves', 'used')) {
                    $table->integer('used')->default(0)->after('available');
                }
                if (!Schema::hasColumn('teacher_leaves', 'remarks')) {
                    $table->text('remarks')->nullable()->after('used');
                }
                if (!Schema::hasColumn('teacher_leaves', 'deleted_at')) {
                    $table->softDeletes();
                }
            });

            // Add foreign key & unique constraint separately to avoid errors if they already exist
            Schema::table('teacher_leaves', function (Blueprint $table) {
                try { $table->foreign('teacher_id')->references('id')->on('personnels')->onDelete('cascade'); } catch (\Throwable $e) {}
                try { $table->unique(['teacher_id', 'leave_type', 'year']); } catch (\Throwable $e) {}
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('teacher_leaves')) {
            Schema::table('teacher_leaves', function (Blueprint $table) {
                // Drop unique constraint if exists
                try { $table->dropUnique('teacher_leaves_teacher_id_leave_type_year_unique'); } catch (\Throwable $e) {}
                try { $table->dropForeign(['teacher_id']); } catch (\Throwable $e) {}
                foreach (['teacher_id','leave_type','year','available','used','remarks','deleted_at'] as $col) {
                    if (Schema::hasColumn('teacher_leaves', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
