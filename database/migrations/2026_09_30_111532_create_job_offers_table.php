<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();

            // Recruteur
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Informations principales
            $table->string('title');
            $table->string('company');
            $table->string('category');
            $table->string('location');

            // Contrat et rémunération
            $table->string('contract');
            $table->string('salary')->nullable();

            // Contenu de l'offre
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();

            // Date limite
            $table->date('deadline')->nullable();

            // Statut
            $table->enum('status', [
                'pending',
                'published',
                'closed',
                'rejected',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};