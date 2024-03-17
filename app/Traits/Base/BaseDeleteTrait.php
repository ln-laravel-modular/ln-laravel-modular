<?php

namespace App\Traits\Base;

use Illuminate\Http\Request;

trait BaseDeleteTrait
{
    public function delete_item(Request $request)
    {
    }
    public function delete_meta_item(Request $request)
    {
        $data = array_merge($request->all(), $data);
        $mid = $data['mid'];
        $return = $this->MetaModel::where("mid", $mid)->delete();
        return $this->success($return);
    }

    public function delete_content_item(Request $request)
    {
        $data = array_merge($request->all(), $data);
        $cid = $data['cid'];
        $return = $this->ContentModel::where("cid", $cid)->delete();
        return $this->success($return);
    }
    public function delete_list(Request $request)
    {
    }
    public function delete_meta_list(Request $request)
    {
        $data = array_merge($request->all(), $data);
        $mids = $data['mids'];
        $return = $this->MetaModel::whereIn("mid", $mids)->delete();
        return $this->success($return);
    }
    public function delete_content_list(Request $request)
    {
        $data = array_merge($request->all(), $data);
        $cids = $data['cids'];
        $return = $this->ContentModel::whereIn("cid", $cids)->delete();
        return $this->success($return);
    }
}
