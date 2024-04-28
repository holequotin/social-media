<?php

use App\Enums\ShowPostType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('group_user', function (Blueprint $table) {
            $table->enum('show_post_type', ShowPostType::getValues())->default(ShowPostType::ALL);
            $table->dateTime('joined_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_user', function (Blueprint $table) {
            $table->dropColumn('show_post_type');
            $table->dropColumn('joined_at');
        });
    }
};
