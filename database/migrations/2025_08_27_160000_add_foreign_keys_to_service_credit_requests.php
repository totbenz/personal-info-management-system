<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_credit_requests', function (Blueprint $table) {
            // Add foreign key constraints if they don't exist
            if (!$this->hasForeignKey('service_credit_requests', 'service_credit_requests_teacher_id_foreign')) {
                $table->foreign('teacher_id')->references('id')->on('personnels')->onDelete('cascade');
            }
            
            if (!$this->hasForeignKey('service_credit_requests', 'service_credit_requests_approved_by_foreign')) {
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_credit_requests', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['approved_by']);
        });
    }
    
    /**
     * Check if a foreign key exists
     */
    private function hasForeignKey($tableName, $foreignKeyName)
    {
        $foreignKeys = \Illuminate\Support\Facades\DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY' 
            AND CONSTRAINT_NAME = ?
        ", [$tableName, $foreignKeyName]);
        
        return count($foreignKeys) > 0;
    }
};
