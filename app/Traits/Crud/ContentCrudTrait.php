<?php

namespace App\Traits\Crud;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

trait ContentCrudTrait
{
  use ContentInsert, ContentDelete, ContentUpdate, ContentSelect, ContentUpsert, ContentIncrease, ContentDecrease, ContentImport, ContentExport, ContentStaging, ContentRelease, ContentLifeCycle;
}

trait ContentInsert
{
  /**
   * @description 新增信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_content_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      $item = $this->insert_item($request)->original['data'];
      // metas
      if ($request->filled('metas') && $this->isuseCrudTrait('meta')) {
        // $return = $this->insert_meta_list(new Request(['data' => $request->input('metas', [])]))->original['data'];
        // foreach ($return['data'] as $meta) {
        //   var_dump($meta);
        // }
      }
      // mids
      if ($request->filled('mids') && $this->issetModel('relationship')) {
        $this->RelationshipModel::insert(array_map(function ($mid) use ($item) {
          return [
            ($this->prefix ?? '') . '_cid' => $item->cid,
            ($this->prefix ?? '') . '_mid' => $mid
          ];
        }, $request->input('mids')));
      }
      // links
      // lids
      // relationships
      return $this->success($item);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 新增列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_content_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      return $this->insert_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentDelete
{

  /**
   * @description 删除信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function delete_content_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      return $this->delete_item($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 删除列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function delete_content_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      $whereIn = $request->input('$whereIn', []);
      if ($request->filled('cids')) {
        $whereIn['cid'] = $request->input('cids');
      } else {
        throw new Exception();
      }
      $request->merge(['$whereIn' => $whereIn]);
      unset($whereIn);
      return $this->delete_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentUpdate
{

  /**
   * @description 更新信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function update_content_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      // 发布，暂存
      $request->filled('$status');
      $return = $this->getReturn($this->update_item($request));
      // 删除原有关联信息
      if ($request->filled('mids') && count($request->input('mids')) > 0) {
        $this->RelationshipModel::where($this->prefix . '_cid', $return['cid'])
          ->whereNotNull($this->prefix . '_mid')
          ->delete();
        $relationships = array_map(function ($item) use ($return) {
          return [
            $this->prefix . '_cid' => $return['cid'],
            $this->prefix . '_mid' => $item,
          ];
        }, $request->input('mids', []));
        // $this->RelationshipModel::insert($relationships);
        $this->upsert_relationship_list(new Request(["data" => $relationships]));
      }
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
  function update_content_list(Request $request)
  {
    try {
      $request = $this->on_request($request, __FUNCTION__);
      $request->merge(['$model' => $this->ContentModel]);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      // unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      foreach ($request->input('data', []) as $item) {
        // $item = array_merge($request->all(), $item);
        $item['$model'] = $this->ContentModel;
        $result = $this->getReturn($this->update_content_item(new Request($item)));
        if ($result instanceof Exception) {
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

trait ContentSelect
{
  /**
   * @description 查询信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_content_item(Request $request)
  {
    try {
      $request->merge([
        '$model' => $this->ContentModel,
        '$with' => array_merge($request->input('$with', []), array_merge(['draft'], array_keys($this->ContentModel::$fields)))
      ]);
      $return = $this->getReturn($this->select_item($request));
      $return['mids'] = array_unique(Arr::pluck($return['relationships'] ?? [], $this->prefix . '_mid'));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  function select_random_content_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      $where = $request->input('$where', []);
      $with = array_merge($request->input('$with', []), array_keys($this->ContentModel::$fields));
      if ($request->filled('type')) array_push($where, ['type', $request->input('type', 'post')]);
      if (!$this->isApiRoute()) {
        array_push($where, ['status', 'publish']);
      } elseif ($request->filled('status')) {
        array_push($where, ['status', $request->input('status', 'publish')]);
      }
      if ($request->filled('parent')) array_push($where, ['parent', $request->input('parent')]);
      $request->merge(['$where' => $where, '$with' => $with]);
      $return = $this->getReturn($this->select_random_item($request));
      return $return;
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_content_list(Request $request)
  {
    try {
      $_logs = [__METHOD__];
      $request->merge(['$model' => $this->ContentModel]);
      $with = $request->input('$with', []);
      // $with = $request->input('$with', $this->ContentModel::$fields);
      // array_push($with, 'fields');
      $where = $request->input('$where', []);
      $whereIn = $request->input('$whereIn', []);
      if ($request->filled('title')) {
        array_push($_logs, "request filled key(title).");
        array_push($where, ['title', 'like', '%' . $request->input('title') . '%']);
      }
      if ($request->filled('slug')) {
        array_push($_logs, "request filled key(slug).");
        array_push($where, ['slug', $request->input('slug')]);
      }
      if ($request->filled('type')) {
        // 查询类型（type）条件
        array_push($_logs, "request filled key(type).");
        $type = $request->input('type');
        // 存在post子类型的情况下，前缀为
        if ($type === 'post_') {
          array_push($where, ['type', 'like', $type . '%']);
        } else {
          array_push($where, ['type', $request->input('type')]);
        }
        unset($type);
      }
      if (!$this->isApiRoute()) {
        $status = $request->input('status', 'publish');
        $status = $status === 'private' ? 'publish' : $status;
        if (empty($status)) $status = 'publish';
        array_push($_logs, "request is api route.");
        array_push($where, ['status', $status]);
        unset($status);
      } elseif ($request->filled('status')) {
        array_push($_logs, "request filled key(status).");
        array_push($where, ['status', $request->input('status')]);
      } else {
        // 查找草稿
        // array_push($with, 'draft');
        // $with['draft'] = function ($query) {
        //   return $query->with(['fields']);
        // };
        //        array_push($with, 'draft');
        // 默认查询非草稿数据
        $whereIn['status'] = ['private', 'publish'];
      }
      if ($request->filled('parent')) {
        array_push($_logs, "request filled key(parent).");
        array_push($where, ['parent', $request->input('parent')]);
      }
      $request->merge(['$with' => $with, '$where' => $where, '$whereIn' => $whereIn,]);
      unset($with, $where, $whereIn);
      $relationships = $request->input('relationships', []);
      if ($request->filled('mids') || $request->filled('mid')) {
        $mids = $request->input('mids', []);
        if ($request->filled('mid')) array_push($mids, $request->input('mid'));
        $mids = array_values(array_filter($mids));
        // $mids = array_values(array_filter($request->input('mids', []), [$request->input('mid', '')]));
        if (!empty($mids)) {
          $relationships[$this->prefix . '_mid'] = array_merge($relationships[$this->prefix . '_mid'] ?? [], $mids);
        }
        unset($mids);
      }
      $request->merge(['relationships' => $relationships]);
      // dump($relationships);
      unset($relationships);
      $return = $this->getReturn($this->select_list($request));
      // dump($return);
      if ($return instanceof Exception) throw $return;

      // array_push($_logs, $return['_logs']);
      // unset($return['_logs']);
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  function select_content_page(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      $return = $this->getReturn($this->select_page($request));
      if ($return instanceof Exception) throw $return;
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
  function select_content_tree(Request $request)
  {
    try {
      //            var_dump([__METHOD__]);
      $request->merge(['$model' => $this->ContentModel, '$with' => ['children']]);
      $where = [];
      if ($request->filled('slug')) array_push($where, ['slug', $request->input('slug')]);
      if ($request->filled('type')) array_push($where, ['type', $request->input('type')]);
      if (!$this->isApiRoute()) {
        array_push($where, ['status', 'publish']);
      } elseif ($request->filled('status')) {
        array_push($where, ['status', $request->input('status', 'publish')]);
      }
      array_push($where, ['parent', $request->input('parent', 0)]);
      $request->merge(['$where' => $where]);
      return $this->select_tree($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  function select_content_count(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      $where = $request->input('$where', []);
      $whereIn = $request->input('$whereIn', []);
      if ($request->filled('title')) array_push($where, ['title', 'like', '%' . $request->input('title') . '%']);
      if ($request->filled('slug')) array_push($where, ['slug', $request->input('slug')]);
      if ($request->filled('type')) array_push($where, ['type', $request->input('type', 'post')]);
      if (!$this->isApiRoute()) {
        array_push($where, ['status', 'publish']);
      } elseif ($request->filled('status')) {
        array_push($where, ['status', $request->input('status')]);
      } else {
        $whereIn['status'] = ['private', 'publish'];
      }
      if ($request->filled('parent')) array_push($where, ['parent', $request->input('parent')]);
      $request->merge(['$where' => $where, '$whereIn' => $whereIn,]);
      unset($where);
      return $this->select_count($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentUpsert
{
  /**
   * @description 替换信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_content_item(Request $request)
  {
    try {
      $_logs = [__METHOD__, $request->all()];
      // dump($_logs);
      $request->merge([
        '$model' => $this->ContentModel,
        '$with' => array_merge($request->input('$with', []), array_merge(['draft'], array_keys($this->ContentModel::$fields)))
      ]);
      // var_dump($request->all());
      $return = $this->getReturn($this->upsert_item($request));
      if ($return instanceof Exception) throw $return;
      array_push($_logs, 'call $this->upsert_item');
      // array_push($_logs, $return['_logs']);
      // artisan_dump($return);
      // artisan_dump($_logs);
      $request->merge(['cid' => $return['cid']]);
      // $return = array_merge($return->toArray(), $this->getReturn($this->import_tree($request)));
      if (sizeof(array_keys($this->ContentModel::$fields)) > 0) {
        $with = array_keys($this->ContentModel::$fields);
        array_push($_logs, "request filled \$with(" . implode(",", $with) . ").");
        // var_dump($_logs);
        foreach ($with as $key) {
          $upserted_field_list = $this->upsert_field_list(new Request([
            "cid" => $return['cid'],
            '$model' => $this->ContentModel::$fields[$key],
            'data' => $request->input($key, [])
          ]));
          $return[$key] = $upserted_field_list['data'];
        }
      }
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 替换列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_content_list(Request $request)
  {
    try {
      $_logs = [__METHOD__];
      $request->merge(['$model' => $this->ContentModel]);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      // unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      array_push($_logs, "foreach.");
      foreach ($request->input('data', []) as $item) {
        $result = $this->getReturn($this->upsert_content_item(new Request($item)));
        if ($result instanceof Exception) throw $result;
        array_push($_logs, [
          'call $this->upsert_content_item',
          $result['_logs'],
        ]);
        if (empty($result)) {
          array_push($return['data'], null);
          $return['failed_count']++;
        } else {
          array_push($return['data'], $result);
          $return['success_count']++;
        }
        unset($result);
      }
      $return['_logs'] = $_logs;
      // artisan_dump($_logs);
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentIncrease
{
}

trait ContentDecrease
{
  function decrease_content_item(Request $request)
  {
    try {
      $request->merge([
        '$model' => $this->ContentModel,
        '$decrement' => 'count',
      ]);
      return $this->decrease_item($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentImport
{
  function import_content(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      return $this->import($request, function ($return, $pathinfo) use ($request) {
        // dump([$return, $pathinfo]);
        $return = Storage::disk('local')->get($return);
        switch ($pathinfo['extension']) {
          case 'json':
            $return = json_decode($return, true);
            // TODO JSON 解析上传
            $return = $this->getReturn($this->import_tree(new Request($return)));
            break;
          case 'md':
            $return = explode(PHP_EOL, $return);
            // dump($collection);
            $return = array_reduce($return, function ($total, $value) {
              if (strlen($value) > 0 && substr($value, 0, 2) === '# ') {
                // 去除标题标志 #
                // array_push($total, ['title' => implode(' ', array_slice(explode(' ', $value), 1)), 'text' => '']);
                array_push($total, ['title' => substr($value, 2), 'text' => '']);
              } elseif (sizeof($total) > 0) {
                $total[sizeof($total) - 1]['text'] .= PHP_EOL . $value;
              }
              return $total;
            }, []);
            // dump($return);
            $request->merge(['data' => $return]);
            $return = $this->getReturn($this->insert_content_list($request));
            break;
          default:
            break;
        }
        return $return;
      });
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentExport
{
  function export_content(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      return $this->export($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentStaging
{
  /**
   * 检测key
   * 没有就新增，
   * 1. 新增status=private
   * 有则
   */
  function staging_content_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);
      $return = $this->getReturn($this->staging_item($request));
      if ($request->filled('fields')) {
        $draft = $return['draft'];
        $return['draft']['fields'] = $this->getReturn($this->insert_field_list(new Request([
          "data" => array_map(function ($item) use ($draft) {
            return array_merge($item, ['cid' => $draft['cid']]);
          }, $request->input('fields'))
        ])))['data'];
      }
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentRelease
{
  function release_content_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->ContentModel]);

      $return = $this->getReturn($this->release_item($request));
      if ($request->filled('fields')) {
        // 清除旧数据
        $this->FieldModel::where([['cid', $return['cid']]])->delete();
        $return['fields'] = $this->getReturn($this->insert_field_list(new Request([
          "data" => array_map(function ($item) use ($return) {
            return array_merge($item, ['cid' => $return['cid']]);
          }, $request->input('fields'))
        ])))['data'];
      }
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  function release_content_list(Request $request)
  {
    try {
      $request->merge([
        '$model' => $this->ContentModel,
        'data' => array_map(function ($cid) {
          return [
            'cid' => $cid,
            '$with' => ['fields'],
          ];
        }, $request->input('cids', []))
      ]);
      $return = $this->getReturn($this->release_list($request));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait ContentLifeCycle
{
  // 调用方法前
  function on_content_request(Request $request, $method)
  {
    return $request;
  }

  function on_content_response(Request $request, $return)
  {
    return $return;
  }
}
trait ContentFaker
{
  function faker_content_item(Request $request)
  {
  }
  function faker_content_list(Request $request)
  {
  }
}