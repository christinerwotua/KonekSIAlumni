<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profile_id')->constrained('talent_profiles');
            $table->string('type');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_documents');
    }
};
