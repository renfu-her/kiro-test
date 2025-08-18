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
        Schema::table('todo_invitations', function (Blueprint $table) {
            $table->foreignId('inviter_id')->nullable()->change();
            $table->foreignId('invitee_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_invitations', function (Blueprint $table) {
            $table->foreignId('inviter_id')->nullable(false)->change();
            $table->foreignId('invitee_id')->nullable(false)->change();
        });
    }
};
