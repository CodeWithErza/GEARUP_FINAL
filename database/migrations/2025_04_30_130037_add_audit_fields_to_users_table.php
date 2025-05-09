<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if 'role' column exists
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('staff')->after('email');
            }
            
            // Check if 'is_active' column exists
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            
            // Add audit fields if they don't exist
            if (!Schema::hasColumn('users', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
            }
        });
        
        // Add foreign key constraints in a separate operation to avoid errors
        Schema::table('users', function (Blueprint $table) {
            // Check if the constraint doesn't already exist by querying information_schema
            $createdByConstraintExists = DB::select("
                SELECT * FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND TABLE_NAME = 'users' 
                AND CONSTRAINT_NAME = 'users_created_by_foreign'
            ");
            
            $updatedByConstraintExists = DB::select("
                SELECT * FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND TABLE_NAME = 'users' 
                AND CONSTRAINT_NAME = 'users_updated_by_foreign'
            ");
            
            if (Schema::hasColumn('users', 'created_by') && empty($createdByConstraintExists)) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (Schema::hasColumn('users', 'updated_by') && empty($updatedByConstraintExists)) {
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop if columns exist
            if (Schema::hasColumn('users', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            
            if (Schema::hasColumn('users', 'updated_by')) {
                $table->dropForeign(['updated_by']);
                $table->dropColumn('updated_by');
            }
        });
    }
};
