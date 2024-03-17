<?php

namespace App\Models;

class Comment extends \App\Models\Model
{
    protected $table = "_comments";

    protected $primaryKey = 'coid';

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\CommentFactory::new();
    }
}