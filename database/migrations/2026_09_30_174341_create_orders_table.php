<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('order_number')->unique();

            // Informations client
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');

            // Livraison
            $table->string('country')->default('Bénin');
            $table->string('city');
            $table->string('neighborhood');
            $table->text('address');
            $table->string('landmark')->nullable();

            // Livraison et paiement
            $table->enum('delivery_method', [
                'home_delivery',
                'pickup',
            ])->default('home_delivery');

            $table->enum('payment_method', [
                'cash_on_delivery',
                'mtn_momo',
                'moov_money',
            ])->default('cash_on_delivery');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
            ])->default('pending');

            // Montants
            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Statut de la commande
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipping',
                'delivered',
                'cancelled',
            ])->default('pending');

            $table->text('customer_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};