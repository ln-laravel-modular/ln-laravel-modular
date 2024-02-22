<?php

namespace App\Traits\Crud;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

trait LinkCrudTrait
{
    use BaseCrudTrait;

    /**
     * @description 新增信息
     * @param Request $request
     * @return array|JsonResponse|mixed|void
     */
    function insert_link_item(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function insert_link_list(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
            return $this->insert_list($request);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * @description 删除信息
     * @param Request $request
     * @return array|JsonResponse|mixed|void
     */
    function delete_link_item(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function delete_link_list(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function update_link_item(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function update_link_list(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function replace_link_item(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function replace_link_list(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function select_link_item(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function select_link_list(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
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
    function select_link_tree(Request $request)
    {
        try {
            $request->merge(['$model' => $this->LinkModel]);
            return $this->select_tree($request);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
