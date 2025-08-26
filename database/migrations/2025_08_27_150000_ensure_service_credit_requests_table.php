<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('service_credit_requests')) {
            Schema::create('service_credit_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id');
                $table->decimal('requested_days', 8, 2);
                $table->date('work_date')->nullable();
                $table->time('morning_in')->nullable();
                $table->time('morning_out')->nullable();
                $table->time('afternoon_in')->nullable();
                $table->time('afternoon_out')->nullable();
                $table->decimal('total_hours', 8, 2)->default(0);
                $table->string('reason');
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['teacher_id','status']);
            });
        } else {
            // Ensure new time columns exist if table was created by the first migration only
            Schema::table('service_credit_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('service_credit_requests','morning_in')) {
                    $table->time('morning_in')->nullable()->after('work_date');
                    $table->time('morning_out')->nullable()->after('morning_in');
                    $table->time('afternoon_in')->nullable()->after('morning_out');
                    $table->time('afternoon_out')->nullable()->after('afternoon_in');
                    $table->decimal('total_hours', 8, 2)->default(0)->after('afternoon_out');
                }
            });
        }
    }

    public function down(): void
    {
        // Intentionally do not drop table to avoid data loss in a repair migration
    }
};
