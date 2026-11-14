<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('users');
            $table->string('title');
            $table->string('company');
            $table->string('location');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_referrals');
    }
};
