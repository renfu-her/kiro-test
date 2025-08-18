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
            // Add member columns for inviter and invitee
            $table->foreignId('inviter_member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->foreignId('invitee_member_id')->nullable()->constrained('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_invitations', function (Blueprint $table) {
            $table->dropForeign(['inviter_member_id']);
            $table->dropForeign(['invitee_member_id']);
            $table->dropColumn(['inviter_member_id', 'invitee_member_id']);
        });
    }
};
