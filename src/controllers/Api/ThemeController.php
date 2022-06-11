<?php
/**
 * Api/Theme Controller
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

class ThemeController extends Controller
{
    /**
     * Get List Action
     */
    public function getListAction($params = [])
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
            'payload' => BlogCore::getThemeList()
        ];
    }

    /**
     * Install Item Action
     */
    public function installItemAction($params = [])
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

    /**
     * Uninstall Item Action
     */
    public function uninstallItemAction($params = [])
    {
        if (0 === count($params)) {
            http_response_code(400);

            return [
                'status' => 'error',
                'text' => 'pointless:api:paramsIsEmpty'
            ];
        }

        // do something

        return [
            'status' => 'ok',
            'text' => 'success'
        ];
    }
}
