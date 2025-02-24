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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');

            $table->string('status')->default('pending');
            $table->string('cobon_number')->unique()->nullable();
            $table->string('receiver')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->string('emira')->nullable();
            $table->string('area')->nullable();
            $table->double('order_fees')->nullable();
            $table->double('delivery_fees')->nullable();
            $table->double('total')->nullable();
            $table->text('notes')->nullable();
            $table->date('date')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
