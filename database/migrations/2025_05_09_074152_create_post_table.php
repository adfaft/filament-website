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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('slug');
            $table->string('post_type');
            $table->string('lang');
            $table->json('translation');
            $table->text('excerpt')->nullable();
            $table->mediumText('content')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('published_at')->nullable();
            $table->json('meta');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
