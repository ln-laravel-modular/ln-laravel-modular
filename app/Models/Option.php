<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Option extends Model
{
  use HasFactory;

  public $table = '_options';
  public $primaryKey = 'name';
  public $incrementing = false;
  protected $hidden = [
    'user',
    'id',
  ];
  protected $fillable = [
    'name',
    'user',
    'type',
    'description',
    'value',
    'created_at',
    'updated_at',
    'release_at',
    'deleted_at'
  ];

  function toArray()
  {
    $return = parent::{__FUNCTION__}();
    if (in_array($return['type'], ['json', 'array', 'object'],)) {
      $return['value'] = unserialize($this->value);
    }
    return $return;
  }
}
