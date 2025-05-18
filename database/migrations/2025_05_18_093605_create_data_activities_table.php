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
        Schema::create('data_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('data_plant_id')->index('data_plant_id');
            $table->string('activity_type');
            $table->date('activity_date');
            $table->longText('details');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_activities');
    }
};
