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
        Schema::create('data_plants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('data_field_id')->index('data_field_id');
            $table->string('name');
            $table->string('variety');
            $table->date('planting_date');
            $table->date('expected_harvest_date');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_plants');
    }
};
