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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();

            // --- THÔNG TIN KHÁCH HÀNG ---
            // user_id có thể null đối với khách vãng lai
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Các trường này dùng để lưu thông tin khi khách không đăng nhập
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->string('customer_address')->nullable();


            // --- THÔNG TIN ĐƠN HÀNG ---
            // ID của người thợ được gán, có thể không có
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('payment_method')->default('cod');
            $table->decimal('total_price', 15, 2)->default(0);
            $table->string('status')->default('pending'); // ví dụ: pending, processing, completed, cancelled
            $table->text('note')->nullable();
            $table->timestamp('installation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};