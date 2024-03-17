<?php

namespace App\Models;

use App\Support\Helpers\ModuleHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Config;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, FamilyTree, TableRelationship;

    public $prefix;
    protected $dates = [
        'created_at',
        'updated_at',
        'release_at',
        'deleted_at'
    ];
    protected $fillable = [
        'created_at',
        'updated_at',
        'release_at',
        'deleted_at'
    ];

    protected static $unguarded = false;

    public function __construct()
    {
        $this->setPrefix();
    }
    public function setPrefix($prefix = null)
    {
        $this->prefix = $prefix ?? Config::get(strtolower(ModuleHelper::current()) . '.db_prefix');
    }
    public function getPrefix()
    {
        return $this->prefix;
    }
    public function getTable()
    {
        // 没有定义表前缀
        if (empty($this->prefix)) return $this->table;
        // 表前缀与当前表名前部分一致
        if (substr($this->table, 0, strlen($this->prefix)) === $this->prefix) return $this->table;
        // 默认
        return $this->prefix . $this->table;
    }


    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}


/**
 * 族谱
 */
trait FamilyTree
{
    /**
     * 父类
     */
    public function parent()
    {
        return $this->hasOne(static::class, $this->primaryKey, $this->parentKey,);
    }

    public function parent_exists()
    {
    }
    public function parents()
    {
        return $this->hasOne(static::class, $this->primaryKey, $this->parentKey,)->with('parent');
    }
    public function root()
    {
        return $this->hasOne(static::class, $this->primaryKey, $this->parentKey,)->with('root');
    }
    /**
     * Retrieve the root parent of the current category.
     * The root parent of a category that has no parent is that category itself.
     *
     * @return \App\Models\Category
     */
    public function getRoot()
    {
        $bubble_keys = [$this[$this->getKeyName()]];
        // $this->bubbule_keys = $this->active_keys ?? [$this[$this->getKeyName()]];
        if ($this->root) {
            if ($this->root->root) {
                $this->root = $this->root->getRoot();
                // dump($this->root->active_keys);
            }
            // array_unshift($bubble_keys);
            $this->root->bubble_keys = array_merge($this->root->bubble_keys ?? [$this->root[$this->getKeyName()]], $bubble_keys);
            return $this->root;
        }
        $this->bubble_keys = array_merge($this->bubble_keys ?? [], $bubble_keys);
        return $this;
        // if ($this->root && $this->root->root) {
        //   return $this->getRoot();
        // }
        // return $this->root;
    }
    /**
     * 子集
     */
    public function children()
    {
        return $this->hasMany(static::class, $this->parentKey, $this->primaryKey);
    }
}

trait TableRelationship
{
    public function metas()
    {
        return $this
            ->hasMany(\App\Models\Relationship::class, $this->prefix . '_' . $this->primaryKey, $this->primaryKey)
            ->leftJoin($this->prefix . "_metas", "_relationships." . $this->prefix . "_mid", '=', $this->prefix . "_metas.mid");
    }

    public function contents()
    {
        return $this
            ->hasMany(\App\Models\Relationship::class, $this->prefix . '_' . $this->primaryKey, $this->primaryKey)
            ->leftJoin($this->prefix . "_contents", "_relationships." . $this->prefix . "_cid", '=', $this->prefix . "_contents.cid");
    }

    public function links()
    {
        return $this
            ->hasMany(\App\Models\Relationship::class, $this->prefix . '_' . $this->primaryKey, $this->primaryKey)
            ->leftJoin($this->prefix . "_links", "_relationships." . $this->prefix . "_lid", '=', $this->prefix . "_links.lid");
    }

    public function relationships()
    {
        return $this->hasMany(\App\Models\Relationship::class, $this->prefix . '_' . $this->primaryKey, $this->primaryKey);
    }

    public function logs()
    {
    }
}

/**
 * 草稿
 */
trait StatusDraft
{
    /**
     * 草稿
     */
    public function draft()
    {
        return $this->hasOne(static::class, $this->parentKey, $this->primaryKey)->with($this->fieldColumns ?? [])->where([['status', 'draft']]);
    }

    /**
     * 草稿列表，草稿记录
     */
    public function drafts()
    {
        return $this->hasMany(static::class, $this->parentKey, $this->primaryKey)->with($this->fieldColumns ?? [])->where([['status', 'draft']]);
    }

    /**
     * 检测是否存在草稿记录
     */
    public function draft_exists()
    {
    }
}
