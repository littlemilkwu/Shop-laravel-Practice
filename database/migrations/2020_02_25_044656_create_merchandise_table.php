<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandise', function (Blueprint $table) {
            // 遞增id
            $table->bigIncrements('id');

            // 商品狀態
            $table->string("status", 1)->default("C");

            // 商品中文名稱
            $table->string("name", 80)->nullable();

            // 商品英文名稱
            $table->string("name_en", 80)->nullable();

            // 商品中文介紹
            $table->text("intro");

            // 商品英文介紹
            $table->text("intro_en");

            // 圖片
            $table->string("photo", 50)->nullable();

            // 商品售價
            $table->integer("price")->default(0);

            // 商品數量
            $table->integer("count")->default(0);

            // 時間戳記
            $table->timestamps();

            // 索引設定
            $table->index(["status"], "merchandise_status_idx");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandise');
    }
}
