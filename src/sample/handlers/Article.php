<?php
/**
 * Article Handler
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;

class Article extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'article';
    }

    /**
     * Init Data
     *
     * @param array
     */
    public function initData($data)
    {
        $this->data = $data;
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList()
    {
        $articleList = $this->data['postBundle']['article'];
        $keys = array_keys($articleList);
        $totalIndex = count($articleList);

        $containerList = [];

        foreach ($keys as $currentIndex => $key) {

            // Set Container
            $container = $articleList[$key];
            $container['url'] = "article/{$container['url']}";

            // Set Paging
            $container['paging'] = [];
            $container['paging']['totalIndex'] = $totalIndex;
            $container['paging']['currentIndex'] = $currentIndex + 1;

            if (isset($keys[$currentIndex - 1])) {
                $prevKey = $keys[$currentIndex - 1];
                $container['paging']['prevTitle'] = $articleList[$prevKey]['title'];
                $container['paging']['prevUrl'] = "article/{$articleList[$prevKey]['url']}";
            }

            if (isset($keys[$currentIndex + 1])) {
                $nextKey = $keys[$currentIndex + 1];
                $container['paging']['nextTitle'] = $articleList[$nextKey]['title'];
                $container['paging']['nextUrl'] = "article/{$articleList[$nextKey]['url']}";
            }

            $containerList[$container['url']] = $container;
        }

        return $containerList;
    }
}
