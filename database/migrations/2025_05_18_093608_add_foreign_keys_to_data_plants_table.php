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
        Schema::table('data_plants', function (Blueprint $table) {
            $table->foreign(['data_field_id'], 'data_plants_ibfk_1')->references(['id'])->on('data_fields')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_plants', function (Blueprint $table) {
            $table->dropForeign('data_plants_ibfk_1');
        });
    }
};
