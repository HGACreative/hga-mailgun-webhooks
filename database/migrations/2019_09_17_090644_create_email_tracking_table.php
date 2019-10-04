<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_tracking', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('to')->nullable();
            $table->string('email');
            $table->string('message_id');
            $table->boolean('sent')->default(0)->index();
            $table->boolean('bounced')->default(0)->index();
            $table->boolean('delivered')->default(0)->index();
            $table->boolean('opened')->default(0)->index();
            $table->unsignedInteger('opens')->default(0)->index();
            $table->boolean('clicked')->default(0)->index();
            $table->unsignedInteger('clicks')->default(0)->index();
            $table->boolean('unsubscribed')->default(0)->index();
            $table->boolean('complained')->after('temporary_fail')->default(0)->index();
            $table->boolean('permanent_fail')->after('bounced')->default(0)->index();
            $table->boolean('temporary_fail')->after('permanent_fail')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_tracking');
    }
}
