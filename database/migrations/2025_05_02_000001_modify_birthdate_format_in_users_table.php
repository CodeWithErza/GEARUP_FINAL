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
        // First, backup existing birthdate data
        $users = DB::table('users')->whereNotNull('birthdate')->get(['id', 'birthdate']);
        
        // Drop the existing birthdate column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('birthdate');
        });
        
        // Add the new birthdate column with the desired format
        Schema::table('users', function (Blueprint $table) {
            $table->string('birthdate')->nullable()->after('is_active');
        });
        
        // Restore the backed up data with the new format
        foreach ($users as $user) {
            try {
                // Create DateTime object from the Y-m-d format
                $date = \DateTime::createFromFormat('Y-m-d', $user->birthdate);
                if ($date) {
                    $newFormat = $date->format('m-d-Y');
                    
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['birthdate' => $newFormat]);
                }
            } catch (\Exception $e) {
                // Log error or handle invalid dates
                continue;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, backup existing birthdate data
        $users = DB::table('users')->whereNotNull('birthdate')->get(['id', 'birthdate']);
        
        // Drop the string birthdate column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('birthdate');
        });
        
        // Recreate the date column
        Schema::table('users', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->after('is_active');
        });
        
        // Restore the backed up data
        foreach ($users as $user) {
            try {
                if ($user->birthdate) {
                    // Create DateTime object from the m-d-Y format
                    $date = \DateTime::createFromFormat('m-d-Y', $user->birthdate);
                    if ($date) {
                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['birthdate' => $date->format('Y-m-d')]);
                    }
                }
            } catch (\Exception $e) {
                // Log error or handle invalid dates
                continue;
            }
        }
    }
}; 