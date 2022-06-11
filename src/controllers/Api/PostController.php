<?php
/**
 * Api/Post Controller
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Controller\Api;

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Web\Controller\Ajax as Controller;

class PostController extends Controller
{
    /**
     * Get List Action
     */
    public function getListAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        $type = (true === isset($params[0])) ? $params[0] : null;

        if (false === in_array($type, [ 'article', 'describe' ])) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:param:typeError'
            ];
        }

        $list = [];

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            if (true === is_string($type) && $type !== $format->getType()) {
                continue;
            }

            $list = BlogCore::getPostList($type, true);
        }

        return [
            'status' => 'ok',
            'text' => 'success',
            'payload' => $list
        ];
    }

    /**
     * Get Item Action
     */
    public function getItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        $url = (true === isset($params[0])) ? $params[0] : null;

        if (false === is_string($url)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:param:urlError'
            ];
        }

        $list = [];

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;
            $type = $format->getType();

            foreach (BlogCore::getPostList($type, true) as $item) {
                if (false === isset($item['params'])
                    || false === isset($item['params']['url'])
                    || $url !== $item['params']['url']
                ) {
                    continue;
                }

                return [
                    'status' => 'ok',
                    'text' => 'success',
                    'payload' => $item
                ];
            }
        }

        http_response_code(404);

        return [
            'status' => 'error',
            'text' => 'pointless:api:postItem:notFound'
        ];
    }

    /**
     * Create Item Action
     */
    public function createItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }




        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }

    /**
     * Update Item Action
     */
    public function updateItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }



        $url = (true === isset($params[0])) ? $params[0] : null;

        if (false === is_string($url)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:param:urlError'
            ];
        }

        $list = [];

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();

            if (true === is_string($type) && $type !== $format->getType()) {
                continue;
            }

            $list = BlogCore::getPostList($type, true);
        }


        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }

    /**
     * Remove Item Action
     */
    public function removeItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        // $query = $this->req->query();
        // $targetType = (true === isset($query['type'])) ? $query['type'] : null;

        // $result = [];

        // foreach (Resource::get('system:constant')['formats'] as $subClassName) {
        //     $className = 'Pointless\\Format\\' . ucfirst($subClassName);
        //     $format = new $className;

        //     $name = $format->getName();
        //     $type = $format->getType();

        //     if (true === is_string($targetType) && $targetType !== $type) {
        //         continue;
        //     }

        //     $result[$type] = BlogCore::getPostList($type, true);
        // }

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }

    /**
     * Get Media List Action
     */
    public function getMediaListAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        $list = [];

        // $query = $this->req->query();
        // $targetType = (true === isset($query['type'])) ? $query['type'] : null;

        // $result = [];

        // foreach (Resource::get('system:constant')['formats'] as $subClassName) {
        //     $className = 'Pointless\\Format\\' . ucfirst($subClassName);
        //     $format = new $className;

        //     $name = $format->getName();
        //     $type = $format->getType();

        //     if (true === is_string($targetType) && $targetType !== $type) {
        //         continue;
        //     }

        //     $result[$type] = BlogCore::getPostList($type, true);
        // }

        return [
            'status' => 'ok',
            'text' => 'success',
            'payload' => $list
        ];
    }

    /**
     * Upload Media Item Action
     */
    public function uploadMediaItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        // $query = $this->req->query();
        // $targetType = (true === isset($query['type'])) ? $query['type'] : null;

        // $result = [];

        // foreach (Resource::get('system:constant')['formats'] as $subClassName) {
        //     $className = 'Pointless\\Format\\' . ucfirst($subClassName);
        //     $format = new $className;

        //     $name = $format->getName();
        //     $type = $format->getType();

        //     if (true === is_string($targetType) && $targetType !== $type) {
        //         continue;
        //     }

        //     $result[$type] = BlogCore::getPostList($type, true);
        // }

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }

    /**
     * Remove Media Item Action
     */
    public function removeMediaItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        // $query = $this->req->query();
        // $targetType = (true === isset($query['type'])) ? $query['type'] : null;

        // $result = [];

        // foreach (Resource::get('system:constant')['formats'] as $subClassName) {
        //     $className = 'Pointless\\Format\\' . ucfirst($subClassName);
        //     $format = new $className;

        //     $name = $format->getName();
        //     $type = $format->getType();

        //     if (true === is_string($targetType) && $targetType !== $type) {
        //         continue;
        //     }

        //     $result[$type] = BlogCore::getPostList($type, true);
        // }

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }
}
