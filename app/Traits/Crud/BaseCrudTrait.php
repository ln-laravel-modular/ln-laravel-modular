<?php

namespace App\Traits\Crud;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait BaseCrudTrait
{
  use BaseInsert, BaseDelete, BaseUpdate, BaseSelect, BaseUpsert, BaseIncrease, BaseDecrease, BaseImport, BaseExport, BaseStaging, BaseRelease, BaseLifeCycle;
}

/**
 * 基础新增
 */
trait BaseInsert
{
  /**
   * @description 新增信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_item(Request $request)
  {
    try {
      $_logs = [];
      $request = $this->on_request($request, __FUNCTION__);
      array_push($_logs, __FUNCTION__);

      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      array_push($_logs, $model);

      array_push($_logs, "Model create record.");
      $return = $model::create($request->all());
      array_push($_logs, $return);

      unset($model);
      $return->save();
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 新增列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_list(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      foreach ($request->input('data', []) as $item) {
        $item = array_merge($request->all(), $item);
        $result = $this->getReturn($this->insert_item(new Request($item)));
        if (empty($result)) {
          array_push($return['data'], null);
          $return['failed_count']++;
        } else {
          array_push($return['data'], $result);
          $return['success_count']++;
        }
        unset($result);
      }
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  function insert_tree(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      foreach ($request->input('data', []) as $item) {
        $item = array_merge($request->all(), $item);
        $result = $this->getReturn($this->insert_item(new Request($item)));
        if (empty($result)) {
          array_push($return['data'], null);
          $return['failed_count']++;
        } else {
          array_push($return['data'], $result);
          $return['success_count']++;
        }
        unset($result);
      }
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 * 删除
 */
trait BaseDelete
{

  /**
   * @description 删除信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function delete_item(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $primaryKey = (new $model())->getKeyName();
      $return = $model::destroy($request->input($primaryKey));
      unset($model, $primaryKey);
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 删除列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function delete_list(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $return = $model::with($this->withClauses($request));
      unset($model);
      $return = $this->whereClauses($request, $return);
      $return = $return->delete();
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 *
 */
trait BaseUpdate
{
  /**
   * @description 更新信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function update_item(Request $request)
  {
    try {
      $_logs = [__METHOD__, $request->all()];
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      array_push($_logs, "get model($model).");
      $primaryKey = (new $model())->getKeyName();
      array_push($_logs, "get primaryKey($primaryKey).");
      $uniqueIndex = (new $model())->uniqueIndex;
      array_push($_logs, "get uniqueIndex($uniqueIndex).");
      array_push($_logs, "select need to update record.");

      if (!$request->filled($primaryKey) && $request->filled($uniqueIndex)) {
        $return = $model::where($uniqueIndex, $request->input($uniqueIndex))->firstOrFail();
        $request->merge([$primaryKey => $return->$primaryKey]);
      }
      $return = $model::findOrFail($request->input($primaryKey));
      array_push($_logs, $return->toArray());
      unset($model, $primaryKey);
      if (!$return) throw new Exception(Lang::get('no exist record.'));
      $return->fill($request->all());
      $return->touch();
      $return->save();

      // if ($request->filled('children')) {
      //   array_push($_logs, "request filled key(children).");
      //   $return['children'] = $this->upsert_list(new Request([
      //     '$model' => $request->input('$model', $this->BaseModel),
      //     "data" => $request->input('children', [])
      //   ]));
      // }

      array_push($_logs, $return->toArray());
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 更新列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function update_list(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      foreach ($request->input('data', []) as $item) {
        $item = array_merge($request->all(), $item);
        $result = $this->getReturn($this->update_item(new Request($item)));
        if (isset($result->original['data'])) {
          array_push($return['data'], $result);
          $return['success_count']++;
        } else {
          array_push($return['data'], null);
          $return['failed_count']++;
        }
        unset($result);
      }
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 *
 */
trait BaseSelect
{
  /**
   * @description 查询信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_item(Request $request)
  {
    $_logs = [__METHOD__];
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $primaryKey = (new $model())->getKeyName();
      $parentColumn = (new $model())->parentColumn;
      $with = $this->withClauses($request);
      if (in_array('draft', $with)) {
        $with = array_filter($with, function ($item) {
          return $item !== 'draft';
        });
        $with['draft'] = function ($query) use ($request, $parentColumn, $with) {
          return $this->whereClauses($request, $query, [$parentColumn])->with($with);
        };
      }
      $return = $model::with($with);
      if ($request->filled($primaryKey)) {
        $return = $return->find($request->input($primaryKey));
      } else if ($request->filled('slug')) {
        $return = $return->where('slug', $request->input('slug'))->first();
      }
      unset($model, $primaryKey);
      if (!$return) throw new Exception(Lang::get('no exist record.'));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询随机信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_random_item(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $return = $model::with($this->withClauses($request));
      unset($model);
      $return = $this->whereClauses($request, $return);
      $return = $return->inRandomOrder()->first();
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_list(Request $request)
  {
    try {
      $_logs = [__METHOD__, $request->all()];
      // var_dump($request->all());
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $parentColumn = (new $model())->parentColumn;
      $return = $model::with($this->withClauses($request));
      // dump($this->withClauses($request));
      if (!empty($request->input('relationships', []))) {
        $relationships = $request->input('relationships');
        $return = $return->with(['relationships' => function ($query) use ($relationships) {
          $query = $this->whereClauses(new Request(['$whereIn' => $relationships]), $query);
        }]);
        unset($relationships);
      }

      // $return = $model::with(['relationships' => function (Builder $query) {
      // $query;
      // }]);
      // $return = $model::with(['draft' => function ($query) use ($request, $parentColumn) {
      //   return $this->whereClauses($request, $query, [$parentColumn])->with($this->withClauses($request));
      // }]);
      // where
      $return = $this->whereClauses($request, $return);
      // $return = $return->relationships();
      // $return = $return->whereHas('relationships', function (Builder $query) {
      //   $query->where('article_mid', 1);
      // });
      unset($model);
      // order by
      $return = $this->orderByClauses($request, $return);
      // if($request->())
      // offset, limit
      // $return = $return->offset($request->input('$offset', 0))->limit($request->input('$offset', 100));
      $return = $return->paginate($request->input('page_size', 15), ['*'], 'page', $request->input('page') ?? request()->query('page'));
      // $return = $return->get();
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      var_dump($e);
      return $this->error($e);
    }
  }

  /**
   * @description 查询分页
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_page(Request $request)
  {
    try {
      $_logs = [__METHOD__, $request->all()];
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $parentColumn = (new $model())->parentColumn;
      $return = $model::with($this->withClauses($request));
      // $return = $model::with(['draft' => function ($query) use ($request, $parentColumn) {
      //   return $this->whereClauses($request, $query, [$parentColumn])->with($this->withClauses($request));
      // }]);
      $return = $this->whereClauses($request, $return);
      unset($model);
      $return = $this->orderByClauses($request, $return);
      $return = $return->paginate($request->input('page_size', 15));
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询树谱
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_tree(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $primaryKey = (new $model())->getKeyName();
      $parentColumn = (new $model())->parentColumn;
      //            var_dump($parentColumn);
      $return = $model::with($this->withClauses($request));
      $return = $model::with(['children' => function ($query) use ($request, $parentColumn) {
        return $this->whereClauses($request, $query, [$parentColumn])->with($this->withClauses($request));
      }]);
      unset($model);
      $return = $this->whereClauses($request, $return);
      $return = $return->get();
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询总量
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_count(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $primaryKey = (new $model())->getKeyName();
      $parentColumn = (new $model())->parentColumn;
      //            var_dump($parentColumn);
      $return = $model::with($this->withClauses($request));
      unset($model);
      $return = $this->whereClauses($request, $return);
      $return = $return->count();
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 *
 */
trait BaseUpsert
{
  /**
   * @description 替换信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_item(Request $request)
  {
    $_logs = [__METHOD__];
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      // var_dump($model);
      $primaryKey = (new $model)->getKeyName();
      array_push($_logs, "get primaryKey($primaryKey).");
      $uniqueIndex = (new $model)->uniqueIndex;
      array_push($_logs, "get uniqueIndex($uniqueIndex).");
      $parentColumn = (new $model)->parentColumn;
      array_push($_logs, "get parentColumn($parentColumn).");
      $exists = false;
      if ($request->filled($primaryKey)) {
        array_push($_logs, "request filled primaryKey($primaryKey).");
        $exists = $model::where($primaryKey, $request->input($primaryKey))->exists();
        array_push($_logs, "Model($model) " . ($exists ? '' : 'not ') . "exists record with primaryKey($primaryKey).");
        // $return = $model::updateOrCreate([$primaryKey => $request->input($primaryKey)], $request->all());
      } else if ($request->filled($uniqueIndex)) {
        array_push($_logs, "request filled uniqueIndex($uniqueIndex).");
        $exists = $model::where($uniqueIndex, $request->input($uniqueIndex))->exists();
        array_push($_logs, "Model($model) " . ($exists ? '' : 'not ') . "exists record with uniqueIndex($uniqueIndex = " . $request->input($uniqueIndex) . ").");
        // $return = $model::updateOrCreate([$uniqueIndex => $request->input($uniqueIndex)], ['name' => "起点"]);
      } else {
        array_push($_logs, "request not filled primaryKey($primaryKey) or uniqueIndex($uniqueIndex).");
      }
      $return = $this->getReturn($exists ? $this->update_item($request) : $this->insert_item($request));
      if ($return instanceof Exception) throw $return;
      if ($request->filled('children')) {
        // artisan_echo("request filled key(children).", __METHOD__);
        array_push($_logs, "request filled key(children).");
        array_push($_logs, 'call $this->upsert_list.');
        $upserted_list = $this->getReturn($this->upsert_list(new Request([
          "data" => array_map(function ($item) use ($primaryKey, $parentColumn, $return) {
            $item[$parentColumn] = $return[$primaryKey];
            return $item;
          }, $request->input('children', [])),
          '$model' => $request->input('$model', $this->BaseModel)
        ])));
        if ($upserted_list instanceof Exception) throw $upserted_list;
        array_push($_logs, $upserted_list['_logs']);
      }

      unset($model, $primaryKey);
      // artisan_dump($_logs);
      // dump($return);
      // dump($_logs);
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      artisan_dump([$e, $request->all()]);
      return $this->error($e);
    }
  }

  /**
   * @description 替换列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_list(Request $request)
  {
    try {
      $_logs = [__METHOD__];
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      array_push($_logs, "get model($model).");
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      array_push($_logs, "foreach.");
      foreach ($request->input('data', []) as $index => $item) {
        $item['$model'] = $model;
        $upserted_item = $this->getReturn($this->upsert_item(new Request($item)));
        if ($upserted_item instanceof Exception) throw $upserted_item;
        array_push($_logs, ["each at $index", $upserted_item['_logs']]);
        if (isset($upserted_item->original['data'])) {
          array_push($return['data'], $upserted_item);
          $return['success_count']++;
        } else {
          array_push($return['data'], null);
          $return['failed_count']++;
        }
        unset($upserted_item);
      }
      unset($model);
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      artisan_dump([$e, $request->all()]);
      return $this->error($e);
    }
  }
}

/**
 * 自增
 */
trait BaseIncrease
{

  function increase_item(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $return = $model::with($this->withClauses($request));
      unset($model);
      $return = $this->whereClauses($request, $return);
      $return = $return->increment($request->input('$increment'));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 * 自减
 */
trait BaseDecrease
{

  function decrease_item(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $return = $model::with($this->withClauses($request));
      unset($model);
      $return = $this->whereClauses($request, $return);
      $return = $return->decrement($request->input('$decrement'));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 * 上传
 */
trait BaseImport
{
  function import(Request $request, $callback = null)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      if (empty($request->file)) throw new Exception("not import file.");
      $extension = trim(strtolower($request->file->extension()));
      $originalExtension = trim(strtolower($request->file->getClientOriginalExtension()));
      if ($extension !== $originalExtension) {
        $return = $request->file->storeAs('import', Str::random(40) . '.' . $originalExtension);
      } else {
        $return = $request->file->store('import');
      }
      // dump(array_keys(config('excel.extension_detector')));
      if (in_array($originalExtension, array_keys(config('excel.extension_detector')))) {
        $return = Excel::import(new $model, $return);
      } else if ($callback) {
        // dump($callback);
        $originalName = $request->file->getClientOriginalName();
        $return = $callback($return, pathinfo($originalName),);
        // [
        //   'name' => $originalName,
        //   'basename' => pathinfo($originalName, PATHINFO_FILENAME),
        //   'extension' => $originalExtension,
        // ]
      }
      // dump([
      //   'file' => $request->file,
      //   'originalName' => $request->file->getClientOriginalName(),
      //   'getClientOriginalExtension' => $originalExtension,
      //   'extension' => $extension,
      //   'return' => $return,
      // ]);
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 * 下载
 */
trait BaseExport
{

  function export(Request $request)
  {
  }
  // function export_tree(Request $request)
  // {
  //   try {
  //     return $this->success(Storage::readStream(Storage::path('template/icon/icon.xlsx')));
  //   } catch (Exception $e) {
  //     return $this->error($e);
  //   }
  // }
}

/**
 * 基础暂存
 */
trait BaseStaging
{
  /**
   *
   */
  function staging_item(Request $request)
  {
    try {
      $_logs = [];
      $request = $this->on_request($request, __FUNCTION__);
      array_push($_logs, __METHOD__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      array_push($_logs, $model);
      $primaryKey = (new $model())->getKeyName();
      array_push($_logs, "primaryKey: " . $primaryKey);
      $parentColumn = (new $model())->parentColumn;
      array_push($_logs, "parentColumn: " . $parentColumn);
      $return = new $model();
      if ($request->filled($primaryKey)) {
        array_push($_logs, "request filled primaryKey.");
        // 判断主键是否存在，并且不为空
        if ($request->input('status') == 'draft') {
          // 如果本身记录的状态就是草稿
          if ($request->input($parentColumn) === 0) {
            // 若父本为默认0，即没有父本
            // 更新状态为默认 status=private 非草稿数据
            $this->update_item(new Request([
              '$model' => $model,
              $primaryKey => $request->$primaryKey,
              'status' => 'private'
            ]));
            // 同时将数据中的父本ID修改为主键ID, 以便于草稿关联正确的父本ID
            $request->merge([$parentColumn => $request->input($primaryKey)]);
          }

          // 更正主键为父本对应的数据
          $request->merge([$primaryKey => $request->input($parentColumn)]);
        }

        $return = new $model($request->all());
        // 删除历史草稿
        $model::where([[$parentColumn, $request->input($primaryKey)], ['status', 'draft']])->delete();
        // dump($return);
      } else {
        array_push($_logs, "request not filled primaryKey.");

        // 没有主键，就新增默认 status=private 非草稿数据
        array_push($_logs, "request merge data.");
        $request->merge(['status' => $request->input('status') === 'draft' ? 'private' : $request->input('status', 'private')]);
        array_push($_logs, $request->all());

        array_push($_logs, "insert 'status=private' record.");
        $return = $this->getReturn($this->insert_item($request));

        array_push($_logs, $return);

        if (empty($return)) throw new Exception('insert private record failed.');
      }
      // 新增 status=draft 状态子数据
      // 主键一般禁止填充
      $request->merge(['status' => 'draft', $parentColumn => $request->input($primaryKey, $return[$primaryKey])]);
      // 更新唯一标识
      if ($request->filled('slug')) $request->merge(['slug' => 'draft-' . $request->input('slug') . '-' . time()]);
      // dump($request->input('status'));
      // dump($request->input($parentColumn));
      // 重置主键
      // $request->merge([$primaryKey => NULL]);
      $return['draft'] = $this->getReturn($this->insert_item($request));
      $return['$logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

/**
 * 发布
 */
trait BaseRelease
{
  /**
   * @description 发布信息
   * 1. 直接更新并发布
   * 2. 更新草稿，并将草稿发布
   *
   */
  function release_item(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      $primaryKey = (new $model())->getKeyName();
      $parentColumn = (new $model())->parentColumn;
      $with = $request->input('$with', []);
      $return = new $model();
      if ($request->filled($primaryKey)) {
        // 判断主键是否存在，并且不为空
        // 查询主键对应数据
        $return = $model::find($request->input($primaryKey));
        if (!$return) throw new Exception(Lang::get('no exist record.'));
        // 根据主键检测存在草稿，默认只存在一个
        $draft = $return->with($with)->where([[$parentColumn, $request->input($primaryKey)], ['status', 'draft']])->first();
        if ($draft) {
          //          dump($draft->fields);
          //          exit();
          if ($draft->slug) $draft->slug = implode('-', array_slice(explode('-', $draft->slug), 1, -1));
          $draft->delete();
          // 更新草稿内容为原始数据
          $draft->fill(['status' => $return->status, $parentColumn => $return[$parentColumn]]);
          // 更新原始内容为草稿数据
          $return->fill($draft->toArray());
        } else {
          $return->fill(['status' => 'publish']);
        }
        $return = $return->fill(['release_at' => now()]);
        $return->touch();
        $return->save();
      }
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 发布列表
   *
   */
  function release_list(Request $request)
  {
    try {
      // dump(__METHOD__);
      // $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      // $return = $model::with($this->withClauses($request));
      // unset($model);
      // $return = $this->whereClauses($request, $return);
      // $return = $return->update(['release_at' => now()]);
      // return $this->success($return);
      $request = $this->on_request($request, __FUNCTION__);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      $model = $request->input('$model', $this->BaseModel);
      unset($model);
      $return = $this->getReturn($this->each_item_func('release_item', $request));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait BaseLifeCycle
{
  // 调用方法前
  function on_request(Request $request, $method)
  {
    $args = explode('_', $method);
    return $request;
  }

  function on_response(Request $request, $return)
  {
    return $return;
  }
  //  function before($method, $vars)
  //  {
  //    return $vars;
  //  }
  // 生成语句后
  //  function generated($sql, $method, $vars)
  //  {
  //    return $sql;
  //  }
  // 执行操作后
  //  function executed($result, $sql, $method, $vars)
  //  {
  //    return $result;
  //  }
  //  function execute_sql($sqls)
  //  {
  //  }
  //  // 调用方法后
  //  function after($result, $sql, $method, $vars)
  //  {
  //    return $result;
  //  }
}

trait BaseFaker
{
  function faker_item(Request $request)
  {
  }

  function faker_list(Request $request)
  {
  }
}
