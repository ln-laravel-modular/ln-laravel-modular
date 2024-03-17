<?php

namespace App\Models;

class Meta extends \App\Models\Model
{

    protected $table = "_metas";

    protected $primaryKey = 'mid';

    protected $fillable = [
        'name',
        'slug',
        'ico',
        'description',
        'type',
        'status',
        'parent',
        'count',
        'order',
        'release_at',
    ];
}