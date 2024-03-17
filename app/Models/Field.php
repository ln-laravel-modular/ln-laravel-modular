<?php

namespace App\Models;

class Field extends \App\Models\Model
{

    protected $table = "_fields";

    protected $primaryKey = 'id';

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\FieldFactory::new();
    }
}