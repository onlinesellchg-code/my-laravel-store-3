<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('orders', function(Blueprint $table){$table->id();$table->string('order_number')->unique();$table->string('customer_name');$table->string('phone',50);$table->string('email')->nullable();$table->text('address');$table->unsignedBigInteger('subtotal');$table->unsignedBigInteger('shipping_cost')->default(0);$table->unsignedBigInteger('discount')->default(0);$table->unsignedBigInteger('total');$table->string('coupon_code')->nullable();$table->string('status')->default('pending')->index();$table->text('notes')->nullable();$table->timestamps();}); } public function down(): void {Schema::dropIfExists('orders');}};
