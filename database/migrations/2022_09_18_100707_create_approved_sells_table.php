<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approved_sells', function (Blueprint $table) {
            $table->id();
            $table->text('products');
            $table->string('bill_no')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('invoice_by')->constrained('users');
            $table->foreignId('checked_by')->constrained('users');
            $table->foreignId('approved_by')->constrained('users');
            $table->float('sub_total');
            $table->integer('discount_percent')->default(0);
            $table->float('grand_total');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_sells');
    }
};
