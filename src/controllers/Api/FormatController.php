<?php
/**
 * Api/Format Controller
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

class FormatController extends Controller
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

        $list = [];

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            if (true === is_string($type) && $type !== $format->getType()) {
                continue;
            }

            $list = $format->getType();
        }

        return [
            'status' => 'ok',
            'text' => 'success',
            'payload' => $list
        ];
    }

    /**
     * Get List Action
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

        $type = (true === isset($params[0])) ? $params[0] : null;

        if (false === in_array($type, [ 'article', 'describe' ])) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:param:typeError'
            ];
        }

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            if (true === is_string($type) && $type !== $format->getType()) {
                continue;
            }

            $item = $format->getType();

            return [
                'status' => 'ok',
                'text' => 'success',
                'payload' => $item
            ];
        }

        http_response_code(404);

        return [
            'status' => 'error',
            'text' => 'pointless:api:postItem:notFound'
        ];
    }
}
