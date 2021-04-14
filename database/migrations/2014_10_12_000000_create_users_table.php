<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['user', 'provider'])->default('user');
            $table->string('name')->nullable();
            $table->string('mobile')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('apiToken')->nullable();
            $table->json('device_token')->nullable();
            $table->boolean('status')->default(0);
            $table->enum('admin_status',['pinned','approved','blocked'])->default('pinned');
            $table->string('image')->nullable();
            $table->text('note')->nullable();
            $table->integer('activation_code')->nullable();
            $table->enum('device_type', ['android', 'ios'])->default('ios');
            $table->string('password')->nullable();
            $table->string('site_url')->nullable();
            $table->bigInteger('package_id')->unsigned()->nullable();
            $table->foreign('package_id')->references('id')->on('packages');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
