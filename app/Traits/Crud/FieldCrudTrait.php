<?php

namespace App\Traits\Crud;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

trait FieldCrudTrait
{
  use FieldInsert, FieldDelete, FieldUpdate, FieldSelect, FieldUpsert, FieldIncrease, FieldDecrease, FieldImport, FieldExport, FieldStaging, FieldRelease;



  /**
   * @description 删除信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function delete_field_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
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
  function delete_field_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      return $this->delete_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 更新信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function update_field_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
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
  function update_field_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      return $this->update_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 替换信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function replace_field_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      return $this->replace_item($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 替换列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function replace_field_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      return $this->replace_list($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 查询信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function select_field_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
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
  function select_field_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
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
  function select_field_tree(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      return $this->select_tree($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}

trait FieldInsert
{
  /**
   * @description 新增信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_field_item(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      if ($request->filled('value')) {
        // var_dump(['filled value']);
        // 如果没有设置数据类型，序列化数据以便于默认str存储
        if (!$request->filled('type') || !in_array($request->input('type'), ['str', 'int', 'float', 'object'])) {
          $request->merge(['str_value' => serialize($request->input('value'))]);
        } else {
          $request->merge([$request->input('type') . '_value' => $request->input('value')]);
        }
      }
      return $this->insert_item($request);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }

  /**
   * @description 新增列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function insert_field_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->FieldModel]);
      $return = $this->getReturn($this->each_item_func('insert_field_item', $request));
      return $this->success($return);
    } catch (Exception $e) {
      return $this->error($e);
    }
  }
}
trait FieldDelete
{
}
trait FieldUpdate
{
}
trait FieldSelect
{
}
trait FieldUpsert
{
  /**
   * @description 替换信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_field_item(Request $request)
  {
    try {
      $_logs = [__METHOD__, $request->all()];
      // var_dump($_logs);
      // var_dump($_logs);
      // $request->merge(['$model' => $this->FieldModel]);
      $return = $this->getReturn($this->upsert_item($request));
      // dump($return);
      if ($request->filled('children')) {
        // dump('request filled children.');
        $request->merge(['data' => $request->input('children', [])]);
        $upserted_field_list = $this->getReturn($this->upsert_field_list($request));
        $return['children'] = $upserted_field_list['data'];
      }
      // $request->merge(['id' => $return->id]);
      // $return = array_merge($return->toArray(), $this->getReturn($this->import_tree($request)));
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
  function upsert_field_list(Request $request)
  {
    try {
      $_logs = [__METHOD__, $request->all()];

      // $request->merge(['$model' => $this->FieldModel]);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      // unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      // dump($_logs);
      foreach ($request->input('data', []) as $item) {
        // dump($item);
        if ($request->filled("cid")) {
          $item['cid'] = $request->input("cid");
          $item['$model'] = $request->input('$model');
        }
        $result = $this->getReturn($this->upsert_field_item(new Request($item)));
        // dump($result);
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
      return $this->success($return);
    } catch (Exception $e) {
      dump($e);
      return $this->error($e);
    }
  }
}
trait FieldIncrease
{
}
trait FieldDecrease
{
}
trait FieldImport
{
}
trait FieldExport
{
}
trait FieldStaging
{
}
trait FieldRelease
{
}