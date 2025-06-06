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
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // kiểu thông báo (new_movie, new_episode,...)
                $table->unsignedBigInteger('user_id')->nullable(); // null = thông báo cho tất cả người dùng
                $table->unsignedBigInteger('movie_id')->nullable();
                $table->string('title');
                $table->text('message');
                $table->string('image')->nullable(); // hình ảnh đi kèm
                $table->string('link')->nullable(); // link đến trang chi tiết
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
