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
    private $sideList = [];

    /**
     * @var array
     */
    private $handlerList = [];

    /**
     * @var array
     */
    private $viewData = [];

    /**
     * Up
     *
     * @return bool
     */
    public function up(): bool
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
            if (false === isset($handlerList[$name])) {
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
            if (false === isset($handlerList[$name])) {
                continue;
            }

            $sideList[$name] = $handlerList[$name]->getSideData();
        }

        // Set List
        $this->handlerList = $handlerList;
        $this->sideList = $sideList;

        // Set View Layout
        $this->view->setLayoutPath('layout');

        return true;
    }

    /**
     * Describe Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function indexAction(array $params = []): bool
    {
        $path = trim($this->req->uri(), '/');
        $path = urldecode($path);

        // Redirect to Page Action
        if ('' === $path) {
            return $this->pageAction($params);
        }

        // Get Container Data List
        $containerList = $this->handlerList['describe']->getContainerDataList();

        if (false === isset($containerList["{$path}/"])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/describe');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList["{$path}/"]
        ]));

        return true;
    }

    /**
     * Article Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function articleAction(array $params = []): bool
    {
        $path = 'article/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['article']->getContainerDataList();

        if (false === isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/article');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));

        return true;
    }

    /**
     * Page Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function pageAction(array $params = []): bool
    {
        $path = 'page/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['page']->getContainerDataList();

        if (false === isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/page');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));

        return true;
    }

    /**
     * Archive Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function archiveAction(array $params = []): bool
    {
        $path = 'archive/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['archive']->getContainerDataList();

        if (false === isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/archive');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));

        return true;
    }

    /**
     * Category Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function categoryAction(array $params = []): bool
    {
        $path = 'category/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['category']->getContainerDataList();

        if (false === isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/category');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));

        return true;
    }

    /**
     * Tag Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function tagAction(array $params = []): bool
    {
        $path = 'tag/' . (0 !== count($params) ? join('/', $params) . '/' : '');
        $path = urldecode($path);

        // Get Container Data List
        $containerList = $this->handlerList['tag']->getContainerDataList();

        if (false === isset($containerList[$path])) {
            http_response_code(404);

            return false;
        }

        // Set View
        $this->view->setContentPath('container/tag');
        $this->view->setData(array_merge($this->viewData, [
            'sideList' => $this->sideList,
            'container' => $containerList[$path]
        ]));

        return true;
    }

    /**
     * Editor Action
     *
     * @param array $params
     *
     * @return bool
     */
    public function editorAction(array $params = []): bool
    {
        $path = trim($this->req->uri(), '/');
        $path = urldecode($path);

        // Set View
        $this->view->setIndexPath(BLOG_EDITOR . '/views/index');
        $this->view->setData($this->viewData);

        return true;
    }
}
