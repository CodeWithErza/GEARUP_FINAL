<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_code')->unique()->nullable();
            $table->string('name');
            $table->string('contact_person');
            $table->string('position')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->enum('payment_terms', ['cod', '15days', '30days', '60days'])->default('cod');
            $table->enum('status', ['active', 'on_hold', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}; 