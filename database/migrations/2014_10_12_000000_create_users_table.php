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
            // 遞增id
            $table->bigIncrements('id');

            // 唯一Email
            $table->string('email', 100);

            // 密碼
            $table->string('password', 60);

            // 預設為General的帳號類別
            $table->string('type', 1)->defalt("G");

            // 綽號
            $table->string('nick', 30);

            $table->rememberToken();
            $table->timestamps();

            // 鍵值索引
            $table->unique(["email"], 'user_email_uk');
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
