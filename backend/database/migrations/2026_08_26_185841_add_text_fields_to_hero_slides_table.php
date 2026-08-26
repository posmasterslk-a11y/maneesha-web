<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('subtitle')->nullable();
            $table->string('title_top')->nullable();
            $table->string('title_bottom')->nullable();
            $table->text('desc')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('btn_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'title_top', 'title_bottom', 'desc', 'btn_text', 'btn_link']);
        });
    }
};
