<?php
/**
 * Api/Config Controller
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Controller\Api;

use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Web\Controller\Ajax as Controller;

class ConfigController extends Controller
{
    /**
     * Load Item Action
     *
     * @param array $params
     *
     * @return array
     */
    public function loadItemAction(array $params = [])
    {
        if (0 < count($params)) {
            http_response_code(404);

            return [
                'status' => 'error',
                'text' => 'pointless:api:notFound'
            ];
        }

        return [
            'status' => 'ok',
            'text' => 'success',
            'payload' => Resource::get('blog:config')
        ];
    }

    /**
     * Save Item Action
     *
     * @param array $params
     *
     * @return array
     */
    public function saveItemAction(array $params = [])
    {
        if (0 < count($params)) {
            http_response_code(404);

            return [
                'status' => 'error',
                'text' => 'pointless:api:notFound'
            ];
        }

        // do something

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }
}
