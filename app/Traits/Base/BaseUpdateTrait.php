<?php

namespace App\Traits\Base;

use Illuminate\Http\Request;

trait BaseUpdateTrait
{
    public function update_item(Request $request)
    {
    }
    public function update_meta_item(Request $request)
    {
        try {
            $mid = $request->mid;
            $meta = $this->MetaModel::find($mid);
            $meta->fill($request->all());
            $meta->touch();
            $meta->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->success($meta);
        $children = $data['children'] ?? [];
        unset($data['children']);
        $contents = $data['contents'] ?? [];
        unset($data['contents']);
        $cid = $data['cid'] ?? NULL;
        unset($data['cid']);
        $cids = $data['cids'] ?? [];
        unset($data['cids']);
        unset($data['created_at']);
        unset($data['release_at']);
        unset($data['updated_at']);
        $mid = $request->mid;
        $return = $data;
        $this->MetaModel::updateOrInsert(['mid' => $mid], $data);
        return $this->success($return);
    }
    public function update_content_item(Request $request)
    {
        try {
            $cid = $request->cid;
            $content = $this->ContentModel::find($cid);
            $content->fill($request->all());
            $content->touch();
            $content->save();
            $mids = $request->mids ?? [];
            // 清除原有关联关系
            $this->RelationshipModel::where('cid', $cid)->delete();
            if (!empty($mids)) {
                $this->RelationshipModel::insert(array_map(function ($mid) use ($cid) {
                    return ["mid" => $mid, 'cid' => $cid];
                }, $mids));
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->success($content);

        $children = $data['children'] ?? [];
        unset($data['children']);
        $contents = $data['contents'] ?? [];
        unset($data['contents']);
        $mid = $data['mid'] ?? NULL;
        unset($data['mid']);
        $mids = $data['mids'] ?? [];
        unset($data['mids']);
        $metas = $data['metas'] ?? [];
        unset($data['metas']);
        unset($data['created_at']);
        unset($data['release_at']);
        unset($data['updated_at']);
        $return = $data;
        // $this->ContentModel::updateOrInsert(['cid' => $cid], $data);


        return $this->success($content);
    }
    public function update_list(Request $request)
    {
    }
    public function update_meta_list(Request $request)
    {
        $return = array_map(function ($item) {
            return $this->update_meta_item(new Request($item))->original['data'];
        }, $request->data);
        return $this->success($return);
    }
    public function update_content_list(Request $request)
    {
        $return = array_map(function ($item) {
            return $this->update_content_item(new Request($item))->original['data'];
        }, $request->data);
        return $this->success($return);
    }
}
