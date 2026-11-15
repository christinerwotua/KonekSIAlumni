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
        Schema::create('mentoring_requests', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->constrained('users');
            $table->foreignId('alumni_id')->constrained('users');
            $table->string('topic');
            $table->text('message');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('response_message')->nullable();
            $table->timestamp('responded_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_requests');
    }
};
