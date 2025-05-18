<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email');

            $table->string('password'); // disimpan sebagai hash
            $table->string('verification_token', 10)->unique();
            $table->text('address')->nullable();
            $table->string('contact')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_registrations');
    }
}
