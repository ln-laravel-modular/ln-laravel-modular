<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 基本关联表
        Schema::create('_relationships', function (Blueprint $table) {
            $table->integer(ModuleHelper::current_config('db_prefix') . '_mid')->nullable();
            $table->integer(ModuleHelper::current_config('db_prefix') . '_cid')->nullable();
            $table->integer(ModuleHelper::current_config('db_prefix') . '_lid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationships');
    }
}
