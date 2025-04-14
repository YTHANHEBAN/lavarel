<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // khóa ngoại tới bảng users
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable(); // địa chỉ
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable(); // xã/phường
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // liên kết với bảng users
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
