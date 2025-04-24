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
        // Create 'personal_access_tokens' table
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Create 'tasks' table if it doesn't exist
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->enum('status', ['pending', 'completed']);
                $table->enum('priority', ['low', 'medium', 'high']);
                $table->integer('order');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Add 'is_admin' column to 'users' table if it doesn't exist
        if (!Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(0);
            });
        }

        // Create 'task_deletions' table
        Schema::create('task_deletions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('task_title');
            $table->timestamp('deleted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop 'task_deletions' table
        Schema::dropIfExists('task_deletions');

        // Remove 'is_admin' column from 'users' table if it exists
        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }

        // Drop 'tasks' table
        Schema::dropIfExists('tasks');

        // Drop 'personal_access_tokens' table
        Schema::dropIfExists('personal_access_tokens');
    }
};