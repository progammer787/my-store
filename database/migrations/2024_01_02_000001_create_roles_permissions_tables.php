<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Super Admin, Manager, etc.
            $table->string('slug')->unique();           // super_admin, manager, etc.
            $table->text('description')->nullable();
            $table->json('permissions');               // ["products","orders","banners",...]
            $table->boolean('is_system')->default(false); // system roles can't be deleted
            $table->timestamps();
        });

        // Add role_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete()->after('role');
            $table->string('avatar')->nullable()->after('role_id');
            $table->timestamp('last_login_at')->nullable()->after('avatar');
        });

        // Activity log
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');          // created, updated, deleted
            $table->string('model');           // Product, Order, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_id', 'avatar', 'last_login_at']);
        });
        Schema::dropIfExists('roles');
    }
};