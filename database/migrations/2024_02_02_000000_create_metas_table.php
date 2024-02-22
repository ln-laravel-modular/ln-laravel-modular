<?php

use App\Support\Helpers\ModuleHelper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 基本标记表
        Schema::create(ModuleHelper::current_config('db_prefix') . '_metas', function (Blueprint $table) {
            $table->id('mid');
            $table->string('slug')->nullable()->unique()->comment('标识');
            $table->string('name')->nullable()->comment('标题');
            $table->string('ico')->nullable()->comment('标徽');
            $table->string('description')->nullable()->comment('描述');
            $table->string('type')->nullable()->comment('类型');
            $table->string('status')->nullable()->comment('状态');
            $table->string('count')->nullable()->default(0)->comment('计数');
            $table->string('order')->nullable()->default(0)->comment('权重');
            $table->string('parent')->nullable()->default(0)->comment('父本');

            $table->timestamps();
            $table->timestamp('release_at')->nullable()->comment('发布时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
    }
}
