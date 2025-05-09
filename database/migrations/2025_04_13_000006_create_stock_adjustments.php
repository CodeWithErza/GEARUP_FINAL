<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
    Schema::create('stock_adjustments', function (Blueprint $table) {
        $table->id();
        $table->string('reference_number')->unique();
        $table->enum('type', ['stock_out', 'adjustment']);
        $table->text('notes')->nullable();
        $table->unsignedBigInteger('processed_by');
        $table->timestamps();
    
        $table->foreign('processed_by')->references('id')->on('users');
    });
    }
    
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}; 