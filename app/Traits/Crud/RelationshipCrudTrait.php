<?php

namespace App\Traits\Crud;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Exception;

trait RelationshipCrudTrait
{
  use  RelationshipUpsert;
}

trait RelationshipUpsert
{
  /**
   * @description 替换信息
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_relationship_item(Request $request)
  {
    try {
      $_logs = [__METHOD__];
      $this->RelationshipModel::where($request->all())->delete();
      $this->RelationshipModel::insert($request->all());
      // $request->merge(['$model' => $this->RelationshipModel]);
      // $return = $this->getReturn($this->upsert_item($request));
      // $return = $this->RelationshipModel::updateOrCreate($request->all(), $request->all());
      $return = [];
      // $return = array_merge($return->toArray(), $this->getReturn($this->import_tree($request)));
      if ($return instanceof Exception) throw $return;
      // array_push($_logs, 'call $this->upsert_item');
      array_push($_logs, $return['_logs'] ?? null);
      $return['_logs'] = $_logs;
      return $this->success($return);
    } catch (Exception $e) {
      var_dump($e);
      return $this->error($e);
    }
  }

  /**
   * @description 替换列表
   * @param Request $request
   * @return array|JsonResponse|mixed|void
   */
  function upsert_relationship_list(Request $request)
  {
    try {
      $request->merge(['$model' => $this->RelationshipModel]);
      // $model = $this->issetModel($request->input('$model', $this->BaseModel));
      // unset($model);
      $return = [
        'data' => [],
        'success_count' => 0,
        'failed_count' => 0,
      ];
      foreach ($request->input('data', []) as $item) {
        $result = $this->getReturn($this->upsert_relationship_item(new Request($item)));
        // dump($return);
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
