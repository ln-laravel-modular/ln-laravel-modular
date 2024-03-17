<?php

namespace App\Models;


class Content extends \App\Models\Model
{
    protected $table = "_contents";

    protected $primaryKey = 'cid';

    protected $fillable = [
        'title',
        'slug',
        'ico',
        'url',
        // 'description',
        'text',
        'type',
        'status',
        'user',
        'parent',
        'count',
        'order',
        'options',
        'suggestion',
        'release_at',
        'download_urls',
    ];
    protected $casts = [
        'release_at' => 'datetime',
        'options' => 'array',
        'download_urls' => 'array',
    ];
}