<?php


namespace App\Traits\Crud;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Exception;

trait MetaCrudTrait
{
  use MetaInsert, MetaDelete, MetaUpdate, MetaSelect, MetaUpsert, MetaIncrease, MetaDecrease, MetaImport, MetaExport, MetaStaging, MetaRelease;
}

trait MetaInsert
{
  /**
   * @description 新增信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_meta_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      return $this->insert_item($request);
      //            $this->isuseCrudTrait('content');
      // TODO 关联新增内容信息
      //            if ($request->filled('contents') && $this->isuseCrudTrait('content')) {
      //                $result = $this->insert_content_list(new Request(['data' => $request->input('contents', [])]))->original['data'];
      //                foreach ($result['data'] as $content) {
      //                    var_dump($content);
      //                }
      //            }
      //            if ($request->filled('cids') && $this->issetModel('relationship')) {
      //                $this->RelationshipModel::insert(array_map(function ($cid) use ($item) {
      //                    return ['cid' => $cid, 'mid' => $item->mid];
      //                }, $request->input('cids', [])));
      //            }
      //            if ($request->filled('links') && $this->isuseCrudTrait('link')) {
      //            }
      //            if ($request->filled('lids') && $this->issetModel('relationship')) {
      //                $this->RelationshipModel::insert(array_map(function ($cid) use ($item) {
      //                    return ['lid' => $cid, 'mid' => $item->mid];
      //                }, $request->input('lids', [])));
      //            }
      // TODO 关联其它表
      //            if ($request->filled('relationships') && $this->issetModel('relationship')) {
      //                $this->RelationshipModel::insert(array_map(function ($cid) use ($item) {
      //                    return ['lid' => $cid, 'mid' => $item->mid];
      //                }, $request->input('lids', [])));
      //            }

      //            if ($request->filled('contents')) {
      //            }
      //            return $this->success($item);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 新增列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_meta_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      return $this->insert_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}
trait MetaDelete
{
  /**
   * @description 删除信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function delete_meta_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
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
  function delete_meta_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      $whereIn = $request->input('$whereIn', []);
      if ($request->filled('mids')) {
        $whereIn['mid'] = $request->input('mids');
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
trait MetaUpdate
{
  /**
   * @description 更新信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function update_meta_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      return $this->update_item($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 更新列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function update_meta_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      return $this->update_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}
trait MetaSelect
{
  /**
   * @description 查询信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_meta_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      return $this->select_item($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_meta_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      $where = $request->input('$where', []);
      $whereIn = $request->input('$whereIn', []);
      if ($request->filled('mids') || $request->filled('mid')) {
        $mids = array_values(array_filter(array_merge([$request->input('mid')],  $request->input('mids', []), $whereIn['mid'] ?? [])));
        if (!empty($mids)) $whereIn['mid'] = $mids;
      }
      // if ($request->filled('mids')) array_push($whereIn, ['mid', $request->input('mids')]);
      // if ($request->filled('mid')) array_push($whereIn, ['mid', $request->input('mid')]);
      if ($request->filled('name')) array_push($where, ['name', 'like', '%' . $request->input('name') . '%']);
      if ($request->filled('slug')) array_push($where, ['slug', $request->input('slug')]);
      if ($request->filled('type')) array_push($where, ['type', $request->input('type')]);
      if (!$this->isApiRoute()) {
        array_push($where, ['status', 'publish']);
      } elseif ($request->filled('status')) {
        array_push($where, ['status', $request->input('status', 'publish')]);
      }
      if ($request->filled('parent')) array_push($where, ['parent', $request->input('parent')]);
      $request->merge(['$where' => $where, '$whereIn' => $whereIn]);
      $whereBetween = [];
      return $this->select_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询树谱
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_meta_tree(Request $request)
  {
    try {
      //            var_dump([__METHOD__]);
      $request->merge(['$model' => $this->MetaModel, '$with' => ['children']]);
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
}
trait MetaUpsert
{
  /**
   * @description 替换信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_meta_item(Request $request)
  {
    try {
      $_logs = [__METHOD__];
      $request->merge(['$model' => $this->MetaModel]);
      $return = $this->getReturn($this->upsert_item($request));
      // $return = array_merge($return->toArray(), $this->getReturn($this->import_tree($request)));
      if ($return instanceof Exception) throw $return;
      array_push($_logs, 'call $this->upsert_item');
      // array_push($_logs, $return['_logs']);
      $request->merge(['mid' => $return->mid]);
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
  function upsert_meta_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->MetaModel]);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      // unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      foreach ($request->input('data', []) as $item) {
        $result = $this->getReturn($this->upsert_meta_item(new Request($item)));
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
trait MetaIncrease
{
}
trait MetaDecrease
{
}
trait MetaImport
{
}
trait MetaExport
{
}
trait MetaStaging
{
}
trait MetaRelease
{
}
trait MetaFaker
{
  function faker_meta_item(Request $request)
  {
  }
  function faker_meta_list(Request $request)
  {
  }
}