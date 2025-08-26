<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_credit_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('service_credit_requests', 'morning_in')) {
                $table->time('morning_in')->nullable()->after('work_date');
                $table->time('morning_out')->nullable()->after('morning_in');
                $table->time('afternoon_in')->nullable()->after('morning_out');
                $table->time('afternoon_out')->nullable()->after('afternoon_in');
                $table->decimal('total_hours', 8, 2)->default(0)->after('afternoon_out');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_credit_requests', function (Blueprint $table) {
            foreach (['morning_in','morning_out','afternoon_in','afternoon_out','total_hours'] as $col) {
                if (Schema::hasColumn('service_credit_requests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
