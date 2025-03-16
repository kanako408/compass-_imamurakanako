<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // サブカテゴリーのテーブルです。メインカテゴリーに関連付けます。
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('main_category_id')->unsigned()->index()->comment('メインカテゴリーID');
            $table->string('sub_category', 100)->index()->comment('サブカテゴリー名');
            $table->timestamps(); // created_at と updated_at を自動で追加

            // 外部キー制約の追加
            $table->foreign('main_category_id')->references('id')->on('main_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
    }
}
