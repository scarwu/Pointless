<?php
/**
 * Api/Post Controller
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Viewer\Controller\Api;

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Web\Controller\Ajax as Controller;

class PostController extends Controller
{
    /**
     * @var array
     */
    private $sideList = [];

    /**
     * @var array
     */
    private $handlerList = [];

    public function up()
    {
        // do nothing
    }

    public function down()
    {
        // do nothing
    }

    public function indexAction()
    {
        $query = $this->req->query();
        $targetType = isset($query['type']) ? $query['type'] : null;

        $result = [];

        foreach (Resource::get('constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();
            $type = $format->getType();

            if (true === is_string($targetType) && $targetType !== $type) {
                continue;
            }

            $result[$type] = BlogCore::getPostList($type);
        }

        $this->res->json($result);
    }
}
