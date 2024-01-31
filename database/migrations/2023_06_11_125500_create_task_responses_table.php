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
        Schema::create('task_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->references('id')->on('user_tasks')->onDelete('cascade');
            $table->string('file_id', 255)->nullable();
            $table->text('text')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_responses');
    }
};
