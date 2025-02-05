<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('be_invite_id');
            $table->unsignedBigInteger('inviter_id');
            $table->unsignedBigInteger('group_id');

            $table->foreign('be_invite_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_invitations');
    }
};
