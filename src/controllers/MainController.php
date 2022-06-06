<?php
/**
 * Main Controller
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Controller;

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\Core\Loader;
use Oni\Web\Controller\Page as Controller;

class MainController extends Controller
{
    /**
     * @var array
     */
    private $assets = [];

    /**
     * @var array
     */
    private $sideList = [];

    /**
     * @var array
     */
    private $handlerList = [];

    /**
     * @var bool
     */
    private $isStaticFile = false;

    /**
     * @var bool
     */
    private $isNotFound = false;

    public function up()
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }

        // Loader Append
        Loader::append('Pointless\Handler', BLOG_HANDLER);
        Loader::append('Pointless\Handler', APP_ROOT . '/handlers');
        Loader::append('Pointless\Extension', BLOG_EXTENSION);
        Loader::append('Pointless\Extension', APP_ROOT . '/extensions');

        // Get Resources
        $systemConstant = Resource::get('system:constant');
        $blogConfig = Resource::get('blog:config');
        $themeConfig = Resource::get('theme:config');
        $themeConstant = Resource::get('theme:constant');

        // Load Posts
        $postBundle = [];

        foreach ($systemConstant['formats'] as $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);

            $instance = new $namespace();
            $type = $instance->getType();

            $postBundle[$type] = [];

            foreach (BlogCore::getPostList($type, true) as $post) {
                if (false === $post['params']['isPublic']) {
                    $post['title'] = "🔒{$post['title']}"; // prepend lock emoji
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
            if (false === isset($handlerList[$name])) {
                $namespace = 'Pointless\\Handler\\' . ucfirst($name);

                $instance = new $namespace();
                $type = $instance->getType();

                $handlerList[$type] = $instance;
                $handlerList[$type]->initData([
                    'system' => [
                        'constant' => $systemConstant
                    ],
                    'blog' => [
                        'config' => $blogConfig
                    ],
                    'theme' => [
                        'config' => $themeConfig,
                        'constant' => $themeConstant
                    ],
                    'postBundle' => $postBundle
                ]);
            }
        }

        // Get Side Data
        $sideList = [];

        foreach ($themeConfig['views']['side'] as $name) {
            if (false === isset($handlerList[$name])) {
                continue;
            }

            $sideList[$name] = $handlerList[$name]->getSideData();
        }

        // Set Assets
        $this->assets = [
            'styles' => [
                'assets/styles/theme.min.css',
                'assets/styles/editor.min.css'
            ],
            'scripts' => [
                'assets/scripts/theme.min.js',
                'assets/scripts/editor.min.js'
            ]
        ];

        // Set Private Variables
        $this->handlerList = $handlerList;
        $this->sideList = $sideList;

        // Set View
        $this->view->setAttr('path', BLOG_THEME . '/views');
        $this->view->setIndexPath(APP_ROOT . '/views/index');
        $this->view->setLayoutPath('layout');
    }

    public function down()
    {
        if (true === $this->isNotFound) {
            http_response_code(404);
        } elseif (false === $this->isStaticFile) {
            $this->res->html($this->view->render());
        }
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
        if (true === is_file(BLOG_ASSET . "/{$path}")) {
            $staticPath = BLOG_ASSET . "/{$path}";
        } elseif (true === is_file(BLOG_THEME . "/{$path}")) {
            $staticPath = BLOG_THEME . "/{$path}";
        } elseif (true === is_file(BLOG_EDITOR . "/{$path}")) {
            $staticPath = BLOG_EDITOR . "/{$path}";
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
            $mimeType = (true === isset($fileInfo['extension']) && true === isset($mimeMapping[$fileInfo['extension']]))
                ? $mimeMapping[$fileInfo['extension']] : mime_content_type($staticPath);

            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($staticPath));

            echo file_get_contents($staticPath);

            $this->isStaticFile = true;

            return true;
        }

        // Get Container Data List
        $containerList = $this->handlerList['describe']->getContainerDataList();

        if (false === isset($containerList["{$path}/"])) {
            $this->isNotFound = true;

            return false;
        }

        // Set View
        $this->view->setContentPath('container/describe');
        $this->view->setData([
            'system' => [
                'constant' => Resource::get('system:constant')
            ],
            'blog' => [
                'config' => Resource::get('blog:config')
            ],
            'theme' => [
                'config' => Resource::get('theme:config'),
                'constant' => Resource::get('theme:constant')
            ],
            'assets' => $this->assets,
            'sideList' => $this->sideList,
            'container' => $containerList["{$path}/"]
        ]);
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

        if (false === isset($containerList[$path])) {
            $this->isNotFound = true;

            return false;
        }

        // Set View
        $this->view->setContentPath('container/article');
        $this->view->setData([
            'system' => [
                'constant' => Resource::get('system:constant')
            ],
            'blog' => [
                'config' => Resource::get('blog:config')
            ],
            'theme' => [
                'config' => Resource::get('theme:config'),
                'constant' => Resource::get('theme:constant')
            ],
            'assets' => $this->assets,
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
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

        if (false === isset($containerList[$path])) {
            $this->isNotFound = true;

            return false;
        }

        // Set View
        $this->view->setContentPath('container/page');
        $this->view->setData([
            'system' => [
                'constant' => Resource::get('system:constant')
            ],
            'blog' => [
                'config' => Resource::get('blog:config')
            ],
            'theme' => [
                'config' => Resource::get('theme:config'),
                'constant' => Resource::get('theme:constant')
            ],
            'assets' => $this->assets,
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
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

        if (false === isset($containerList[$path])) {
            $this->isNotFound = true;

            return false;
        }

        // Set View
        $this->view->setContentPath('container/archive');
        $this->view->setData([
            'system' => [
                'constant' => Resource::get('system:constant')
            ],
            'blog' => [
                'config' => Resource::get('blog:config')
            ],
            'theme' => [
                'config' => Resource::get('theme:config'),
                'constant' => Resource::get('theme:constant')
            ],
            'assets' => $this->assets,
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
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

        if (false === isset($containerList[$path])) {
            $this->isNotFound = true;

            return false;
        }

        // Set View
        $this->view->setContentPath('container/category');
        $this->view->setData([
            'system' => [
                'constant' => Resource::get('system:constant')
            ],
            'blog' => [
                'config' => Resource::get('blog:config')
            ],
            'theme' => [
                'config' => Resource::get('theme:config'),
                'constant' => Resource::get('theme:constant')
            ],
            'assets' => $this->assets,
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
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

        if (false === isset($containerList[$path])) {
            $this->isNotFound = true;

            return false;
        }

        // Set View
        $this->view->setContentPath('container/tag');
        $this->view->setData([
            'system' => [
                'constant' => Resource::get('system:constant')
            ],
            'blog' => [
                'config' => Resource::get('blog:config')
            ],
            'theme' => [
                'config' => Resource::get('theme:config'),
                'constant' => Resource::get('theme:constant')
            ],
            'assets' => $this->assets,
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]);
    }
}
