<?php
/**
 * Main Controller
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Viewer\Controller;

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Web\Controller\Page as Controller;

class MainController extends Controller
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

            foreach (BlogCore::getPostList($type) as $post) {
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

        // Set View
        $this->view->setLayoutPath('index');
    }

    public function down()
    {
        $this->res->html($this->view->render());
    }

    /**
     * Describe Action
     *
     * @param array $params
     */
    public function indexAction($params = [])
    {
        $path = trim($this->req->uri(), '/');
        $path = urldecode($path);

        // Redirect to Page Action
        if ('' === $path) {
            return $this->pageAction($params);
        }

        // Check Assets File
        if (is_file(BLOG_ASSET . "/{$path}")) {
            $staticPath = BLOG_ASSET . "/{$path}";
        } elseif (is_file(BLOG_THEME . "/{$path}")) {
            $staticPath = BLOG_THEME . "/{$path}";
        } else {
            $staticPath = null;
        }

        if (null !== $staticPath) {
            $mimeMapping = [
                'html' => 'text/html',
                'css' => 'text/css',
                'js' => 'text/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',

                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',

                'woff' => 'application/font-woff',
                'ttf' => 'font/opentype'
            ];

            $fileInfo = pathinfo($staticPath);
            $mimeType = isset($fileInfo['extension']) && isset($mimeMapping[$fileInfo['extension']])
                ? $mimeMapping[$fileInfo['extension']] : mime_content_type($staticPath);

            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($staticPath));

            echo file_get_contents($staticPath);

            exit(0);
        }

        // Get Container Data List
        $containerList = $this->handlerList['describe']->getContainerDataList();

        if (isset($containerList["{$path}/"])) {

            // Set View
            $this->view->setContentPath('container/describe');
            $this->view->setData([
                'systemConstant' => Resource::get('system:constant'),
                'systemConfig' => Resource::get('system:config'),
                'themeConfig' => Resource::get('theme:config'),
                'sideList' => $this->sideList,
                'container' => $containerList["{$path}/"]
            ]);
        } else {
            exit(0);
        }
    }

    /**
     * Article Action
     *
     * @param array $params
     */
    public function articleAction($params = [])
    {
        $path = 'article/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['article']->getContainerDataList();

        // Set View
        $this->view->setContentPath('container/article');
        $this->view->setData([
            'systemConstant' => Resource::get('system:constant'),
            'systemConfig' => Resource::get('system:config'),
            'themeConfig' => Resource::get('theme:config'),
            'sideList' => $this->sideList,
            'container' => isset($containerList[$path])
                ? $containerList[$path] : []
        ]);
    }

    /**
     * Page Action
     *
     * @param array $params
     */
    public function pageAction($params = [])
    {
        $path = 'page/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['page']->getContainerDataList();

        // Set View
        $this->view->setContentPath('container/page');
        $this->view->setData([
            'systemConstant' => Resource::get('system:constant'),
            'systemConfig' => Resource::get('system:config'),
            'themeConfig' => Resource::get('theme:config'),
            'sideList' => $this->sideList,
            'container' => isset($containerList[$path])
                ? $containerList[$path] : []
        ]);
    }

    /**
     * Archive Action
     *
     * @param array $params
     */
    public function archiveAction($params = [])
    {
        $path = 'archive/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['archive']->getContainerDataList();

        // Set View
        $this->view->setContentPath('container/archive');
        $this->view->setData([
            'systemConstant' => Resource::get('system:constant'),
            'systemConfig' => Resource::get('system:config'),
            'themeConfig' => Resource::get('theme:config'),
            'sideList' => $this->sideList,
            'container' => isset($containerList[$path])
                ? $containerList[$path] : []
        ]);
    }

    /**
     * Category Action
     *
     * @param array $params
     */
    public function categoryAction($params = [])
    {
        $path = 'category/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['category']->getContainerDataList();

        // Set View
        $this->view->setContentPath('container/category');
        $this->view->setData([
            'systemConstant' => Resource::get('system:constant'),
            'systemConfig' => Resource::get('system:config'),
            'themeConfig' => Resource::get('theme:config'),
            'sideList' => $this->sideList,
            'container' => isset($containerList[$path])
                ? $containerList[$path] : []
        ]);
    }

    /**
     * Tag Action
     *
     * @param array $params
     */
    public function tagAction($params = [])
    {
        $path = 'tag/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['tag']->getContainerDataList();

        // Set View
        $this->view->setContentPath('container/tag');
        $this->view->setData([
            'systemConstant' => Resource::get('system:constant'),
            'systemConfig' => Resource::get('system:config'),
            'themeConfig' => Resource::get('theme:config'),
            'sideList' => $this->sideList,
            'container' => isset($containerList[$path])
                ? $containerList[$path] : []
        ]);
    }
}
