<?php

namespace App\Traits\Base;

use Illuminate\Http\Request;

trait BaseSelectTrait
{
    public function select_item(Request $request)
    {
    }

    public function select_meta_item(Request $request)
    {
        $mid = $request->mid;
        $slug = $request->slug;
        $return = $this->MetaModel::where("mid", $mid)->orWhere("slug", $slug)->first();
        return $this->success($return);
    }

    public function select_content_item(Request $request)
    {
        $with = $request->with ?? ['children', 'parent'];
        if ($this->MetaModel) array_push($with, 'metas');
        if ($this->FieldModel) array_push($with, 'fields');
        $return = $this->ContentModel::with($with);
        $return = empty($request->cid) ? $return : $return->where('cid', $request->cid);
        $return = empty($request->slug) ? $return : $return->where('slug', $request->slug);
        $return = $return->first();
        return $this->success($return);
    }

    public function select_comment_item(Request $request)
    {
    }

    public function select_list(Request $request)
    {
    }

    public function select_meta_list(Request $request)
    {
        $return = $this->MetaModel::with(['children']);
        $name = $request->name; // like
        $return = empty($name) ? $return : $return->where('name', 'like', '%' . $name . '%');
        $slug = $request->slug; // where
        $return = empty($slug) ? $return : $return->where('slug', $slug);
        $type = $request->type; // whereIn
        $return = empty($type) ? $return : $return->where('type', $type);
        $parent = $request->parent; // where
        $return = empty($parent) && $parent !== 0 ? $return : $return->where('parent', $parent);

        return $this->success($return->paginate($request->page_size ?? 15));
    }

    public function select_content_list(Request $request)
    {
        try {
            $with = $request->input('with', []);
        } catch (\Exception $e) {
            return $this->error($e);
        }
        $return = $this->ContentModel::with(['children']);
        $title = $request->title; // like
        $return = empty($title) ? $return : $return->where('title', 'like', '%' . $title . '%');
        $slug = $request->slug; // where
        $return = empty($slug) ? $return : $return->where('slug', $slug);
        $type = $request->type; // whereIn
        $return = empty($type) ? $return : $return->where('type', $type);
        $parent = $request->parent; // where
        $return = empty($parent) && $parent !== 0 ? $return : $return->where('parent', $parent);
        return $this->success($return->paginate($request->page_size ?? 15));
    }

    public function select_comment_list(Request $request)
    {
    }

    /**
     * @description 查询树谱
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed/void
     */
    public function select_tree(Request $request)
    {
    }

    /**
     * @description 查询标识树谱
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function select_meta_tree(Request $request)
    {
        try {
            $with = $request->input('with', []);
            $type = $request->type; // whereIn
            $return = $this->MetaModel::with(['children' => function ($query) {
                $query = empty($type) ? $query : $query->where('type', $type);
                return $query->with(['children']);
            }]);
            $return = empty($type) ? $return : $return->where('type', $type);
            $parent = $request->parent; // where
//            var_dump($parent);
//            var_dump(empty($parent) && $parent !== 0 ? $return : 123);
            $return = empty($parent) && $parent !== 0 ? $return : $return->where('parent', $parent);
            return $this->success($return->get());
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * @description 查询内容树谱
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|mixed|void
     */
    public function select_content_tree(Request $request)
    {
        try {
            $with = $request->input('with', []);
            $type = $request->type; // whereIn
            $return = $this->ContentModel::with(['children' => function ($query) {
                $query = empty($type) ? $query : $query->where('type', $type);
                return $query->with(['children']);
            }]);
            $return = empty($type) ? $return : $return->where('type', $type);
            $parent = $request->parent; // where
            // var_dump($parent);
            $return = empty($parent) && $parent !== 0 ? $return : $return->where('parent', $parent);
            // var_dump($return->get());
            return $this->success($return->get());
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }
}
