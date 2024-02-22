<?php

namespace App\Traits\Base;

use Exception;
use Illuminate\Http\Request;

trait BaseInsertTrait
{
    /**
     * @description 新增信息
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function insert_item(Request $request)
    {
    }

    /**
     * @description 新增标识信息
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function insert_meta_item(Request $request)
    {
        try {
            $meta = $this->MetaModel::create($request->all());
            $meta->save();
        } catch (\Exception $e) {
            return $this->error($e);
        }
        return $this->success($meta);
        // foreach ($data as $key => $value) {
        //     $result = $this->call_self_func($key, $value);
        //     if (!empty($result)) unset($data[$key]);
        //     unset($result);
        // }
        $children = $data['children'] ?? [];
        unset($data['children']);
        $contents = $data['contents'] ?? [];
        unset($data['contents']);
        $cid = $data['cid'] ?? NULL;
        unset($data['cid']);
        $cids = $data['cids'] ?? [];
        unset($data['cids']);
        $return = $data;
        $mid = $this->MetaModel::insertGetId($data);
        $return['mid'] = $mid;
        if (!empty($children) && $this->insert_meta_list) {
            $return['children'] = $this->insert_meta_list(new Request(["data" => array_map(function ($item) use ($mid) {
                return array_merge($item, ["parent" => $mid]);
            }, $children)]))->original['data'];
        }
        if (!empty($contents) && $this->insert_content_list) {
            $return['contents'] = $this->insert_content_list(new Request(["data" => array_map(function ($item) use ($mid) {
                return array_merge($item, ['mid' => $mid]);
            }, $contents)]))->original['data'];
        }
        return $this->success($return);
    }

    /**
     * @description 新增内容信息
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function insert_content_item(Request $request)
    {
        try {
            // var_dump($request->all());
            $content = $this->ContentModel::create($request->all());
            // var_dump($content->toArray());
            $content->save();
            if (!empty($request->children) && !empty($this->ContentModel)) {
                $children = $request->children;
                $content->children = $this->insert_content_list(new Request(["data" => array_map(function ($item) use ($content) {
                    return array_merge($item, ["parent" => $content->cid]);
                }, $children)]))->original['data'];
                unset($children);
            }
            if (!empty($request->mid) && !empty($this->RelationshipModel)) {
                $mid = $request->mid;
                $this->RelationshipModel::insert(["mid" => $mid, "cid" => $content->cid]);
                unset($mid);
            }
            if (!empty($request->mids) && !empty($this->RelationshipModel)) {
                $mids = $request->mids;
                $this->RelationshipModel::insert(array_map(function ($mid) use ($content) {
                    return ["mid" => $mid, "cid" => $content->cid];
                }, $mids));
                unset($mids);
            }
            if (!empty($metas) && !empty($this->MetaModel)) {
                $metas = $request->metas;
                $this->insert_meta_list(new Request(["data" => array_map(function ($item) use ($content) {
                    return array_merge($item, ['cid' => $content->cid]);
                }, $metas)]))->original['data'];
                unset($metas);
            }
            return $this->select_content_item(new Request(['cid' => $content->cid]));
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    public function insert_list(Request $request)
    {
    }

    public function insert_meta_list(Request $request)
    {
        $return = array_map(function ($item) {
            return $this->insert_meta_item(new Request($item))->original['data'];
        }, $request->data ?? []);
        return $this->success($return);
    }

    public function insert_content_list(Request $request)
    {
        try {
            $return = array_map(function ($item) {
                $return = $this->insert_content_item(new Request($item));
                return $return->original['data'];
            }, $request->data ?? []);
            return $this->success($return);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    public function insert_field_list(Request $request)
    {
    }
}
