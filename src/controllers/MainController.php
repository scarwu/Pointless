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
    private array $sideList = [];
    private array $handlerList = [];
    private array $viewData = [];

    #[\Override]
    public function up(): mixed
    {
        // Get Resources
        $systemConstant = Resource::get('system:constant');
        $blogConfig = Resource::get('blog:config');
        $themeConfig = Resource::get('theme:config');
        $themeConstant = Resource::get('theme:constant');

        // Set View Data
        $this->viewData = [
            'system' => [
                'constant' => $systemConstant
            ],
            'blog' => [
                'config' => $blogConfig
            ],
            'theme' => [
                'config' => $themeConfig,
                'constant' => $themeConstant
            ]
        ];

        // Load Posts
        $postBundle = [];

        foreach ($systemConstant['formats'] as $name) {
            $className = 'Pointless\\Format\\' . ucfirst($name);

            $instance = new $className();
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
            if (!isset($handlerList[$name])) {
                $className = 'Pointless\\Handler\\' . ucfirst($name);

                $instance = new $className();
                $type = $instance->getType();

                $handlerList[$type] = $instance;
                $handlerList[$type]->initData(array_merge($this->viewData, [
                    'postBundle' => $postBundle
                ]));
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

        // Set List
        $this->handlerList = $handlerList;
        $this->sideList = $sideList;

        // Set View Layout
        $this->view->setLayoutPath('layout');
    }

    /**
     * Describe Action
     *
     * @param array $params
     */
    public function indexAction(array $params = []): mixed
    {
        $path = trim($this->req->uri(), '/');
        $path = urldecode($path);

        // Redirect to Page Action
        if ('' === $path) {
            return $this->pageAction($params);
        }

        // Get Container Data List
        $containerList = $this->handlerList['describe']->getContainerDataList();

        if (!isset($containerList["{$path}/"])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/describe');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList["{$path}/"]
        ]));
    }

    /**
     * Article Action
     *
     * @param array $params
     */
    public function articleAction(array $params = []): mixed
    {
        $path = 'article/' . (0 !== count($params) ? implode('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['article']->getContainerDataList();

        if (!isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/article');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));
    }

    /**
     * Page Action
     *
     * @param array $params
     */
    public function pageAction(array $params = []): mixed
    {
        $path = 'page/' . (0 !== count($params) ? implode('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['page']->getContainerDataList();

        if (!isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/page');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));
    }

    /**
     * Archive Action
     *
     * @param array $params
     */
    public function archiveAction(array $params = []): mixed
    {
        $path = 'archive/' . (0 !== count($params) ? implode('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['archive']->getContainerDataList();

        if (!isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/archive');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));
    }

    /**
     * Category Action
     *
     * @param array $params
     */
    public function categoryAction(array $params = []): mixed
    {
        $path = 'category/' . (0 !== count($params) ? implode('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['category']->getContainerDataList();

        if (!isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/category');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));
    }

    /**
     * Tag Action
     *
     * @param array $params
     */
    public function tagAction(array $params = []): mixed
    {
        $path = 'tag/' . (0 !== count($params) ? implode('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['tag']->getContainerDataList();

        if (!isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/tag');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));
    }

    /**
     * Editor Action
     *
     * @param array $params
     */
    public function editorAction(array $params = []): mixed
    {
        $path = trim($this->req->uri(), '/');
        $path = urldecode($path);

        // Set View
        $this->view->setIndexPath(BLOG_EDITOR . '/views/index');
        $this->view->setData($this->viewData);
    }
}
