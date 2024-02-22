<?php

namespace App\Traits\Base;

use Illuminate\Http\Request;

trait BaseImportTrait
{
  // public function import(Request $request)
  // {
  //     // 清空数据表
  //     // $truncate = $request->truncate ?? false;
  //     if ($request->truncate ?? false) {
  //         empty($this->MetaModel) ? NULL : $this->MetaModel::truncate();
  //         empty($this->ContentModel) ? NULL : $this->ContentModel::truncate();
  //         empty($this->RelationshipModel) ? NULL : $this->RelationshipModel::truncate();
  //         empty($this->FieldModel) ? NULL : $this->FieldModel::truncate();
  //         empty($this->CommentModel) ? NULL : $this->CommentModel::truncate();
  //         empty($this->LinkModel) ? NULL : $this->LinkModel::truncate();
  //     }

  //     // $initialization = $request->initialization ?? false;
  //     // 使用本地初始化数据文件
  //     if ($request->initialization ?? false) {
  //         $filename = explode("/", $request->path())[1];
  //         $file = json_decode(app('files')->get('database/initializations/' . $filename . '.json'), true);
  //     }
  //     // $file = json_decode(app('files')->get('database/initializations/openapi.json'), true);
  //     $return = [];
  //     !isset($file['metas']) ?: $return['metas'] = $this->insert_meta_list(new Request(['data' => $file['metas']]))->original['data'];
  //     !isset($file['contents']) ?: $return['contents'] = $this->insert_content_list(new Request(['data' => $file['contents']]))->original['data'];
  //     !isset($file['options']) ?:
  //         $return['options'] =  array_map(function ($item) {
  //             if ($item['type'] == 'json' && is_array($item['value'])) $item['value'] = json_encode($item['value'], JSON_UNESCAPED_UNICODE);
  //             return $this->success($this->OptionModel::updateOrInsert(['name' => $item['name']], $item))->original['data'];
  //         }, $file['options']);

  //     return $this->success($return);
  // }
  // public function import_item(Request $request)
  // {
  // }
  // public function import_meta_item(Request $request)
  // {
  // }
  // public function import_content_item(Request $request)
  // {
  // }
  // public function import_list(Request $request)
  // {
  // }
  // public function import_meta_list(Request $request)
  // {
  // }
  // public function import_content_list(Request $request)
  // {
  // }
}
