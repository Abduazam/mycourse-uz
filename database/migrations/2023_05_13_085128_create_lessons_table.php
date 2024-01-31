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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('file', 255)->nullable();
            $table->text('description');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('lesson_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->string('file', 255);
            $table->text('description')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_files');
        Schema::dropIfExists('lessons');
    }
};
