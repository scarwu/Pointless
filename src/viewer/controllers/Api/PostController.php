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

use Pointless\Library\Misc;
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
        // Load System Consatnt
        require APP_ROOT . '/constant.php';

        $systemConstant = $constant;

        // Load System Config
        require BLOG_ROOT . '/config.php';

        $systemConfig = $config;

        // Load Theme Config
        require BLOG_THEME . '/config.php';

        $themeConfig = $config;

        // Set Resource
        Resource::set('system:constant', $systemConstant);
        Resource::set('system:config', $systemConfig);
        Resource::set('theme:config', $themeConfig);

        // Load Posts
        $postBundle = [];

        foreach ($systemConstant['formats'] as $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);

            $instance = new $namespace();
            $type = $instance->getType();

            $postBundle[$type] = [];

            foreach (Misc::getPostList($type) as $post) {
                if (false === $post['isPublic']) {
                    $post['title'] = "🔒 {$post['title']}"; // append lock emoji before
                }

                $postBundle[$type][] = $instance->convertPost($post);
            }
        }

        foreach ($postBundle as $type => $post) {
            $postBundle[$type] = array_reverse($post);
        }

        // Rendering HTML Pages
        $handlerList = [];

        foreach ($themeConfig['handlers'] as $name) {
            if (!isset($handlerList[$name])) {
                $namespace = 'Pointless\\Handler\\' . ucfirst($name);

                $instance = new $namespace();
                $type = $instance->getType();

                $handlerList[$type] = $instance;
                $handlerList[$type]->initData([
                    'systemConstant' => $systemConstant,
                    'systemConfig' => $systemConfig,
                    'themeConfig' => $themeConfig,
                    'postBundle' => $postBundle
                ]);
            }
        }

        // Get Side Data
        $sideList = [];

        foreach ($themeConfig['views']['side'] as $name) {
            if (!isset($handlerList[$name])) {
                continue;
            }

            $sideList[$name] = $handlerList[$name]->getSideData();
        }

        // Set Private Variables
        $this->handlerList = $handlerList;
        $this->sideList = $sideList;
    }

    public function down()
    {
        // do nothing
    }

    public function indexAction()
    {
        $result = [];

        foreach (Resource::get('system:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();
            $type = $format->getType();

            $result[$type] = Misc::getPostList($type, false);
        }

        $this->res->json($result);
    }
}
