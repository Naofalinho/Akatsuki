<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);

            $table->string('gender')->nullable();
            $table->string('city')->nullable();

            $table->string('position');
            $table->string('domain')->nullable();

            $table->string('education_level')->nullable();

            $table->unsignedInteger('experience_years')
                ->default(0);

            $table->string('cv_path');

            $table->string('cover_letter_path')
                ->nullable();

            $table->text('message')
                ->nullable();

            $table->string('availability')
                ->nullable();

            $table->enum('status', [
                'pending',
                'reviewing',
                'accepted',
                'rejected',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};  