<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 基本内容表
        Schema::create(ModuleHelper::current_config('db_prefix') . '_contents', function (Blueprint $table) {
            $table->id('cid');
            $table->string('slug')->nullable()->unique()->comment('标识');
            $table->string('title')->nullable()->comment('标题');
            $table->string('ico')->nullable()->comment('标徽');
            $table->string('description')->nullable()->comment('描述');
            $table->string('type')->nullable()->comment('类型');
            $table->string('status')->nullable()->comment('状态');
            $table->longText('text')->nullable()->comment('内容');

            $table->string('template')->nullable()->comment('模板');
            $table->string('views')->nullable()->comment('视图');
            $table->string('count')->nullable()->comment('计数');
            $table->string('order')->nullable()->comment('权重');
            $table->string('parent')->nullable()->comment('父本');

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
        Schema::dropIfExists(ModuleHelper::current_config('db_prefix') . '_contents');
    }
}
