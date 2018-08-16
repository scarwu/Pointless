<?php
/**
 * Category Handler
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;

class Category extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'category';
    }

    /**
     * Init Data
     *
     * @param array
     */
    public function initData($data)
    {
        $data['articleByCategory'] = [];

        foreach ($data['postBundle']['article'] as $post) {
            $category = $post['category'];

            if (!isset($data['articleByCategory'][$category])) {
                $data['articleByCategory'][$category] = [];
            }

            $data['articleByCategory'][$category][] = $post;
        }

        uasort($data['articleByCategory'], function ($a, $b) {
            if (count($a) === count($b)) {
                return 0;
            }

            return count($a) > count($b) ? -1 : 1;
        });

        $this->data = $data;
    }

    /**
     * Get Side Data
     *
     * @return array
     */
    public function getSideData()
    {
        return $this->data['articleByCategory'];
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList()
    {
        $articleList = $this->data['articleByCategory'];
        $keys = array_keys($articleList);
        $totalIndex = count($articleList);

        $containerList = [];

        foreach ($keys as $currentIndex => $key) {

            // Set Post
            $container = [];
            $container['title'] ="Category: {$key}";
            $container['url'] = "category/{$key}/";
            $container['list'] = $articleList[$key];

            // Set Paging
            $container['paging'] = [];
            $container['paging']['totalIndex'] = $totalIndex;
            $container['paging']['currentIndex'] = $currentIndex + 1;

            if (isset($keys[$currentIndex - 1])) {
                $prevKey = $keys[$currentIndex - 1];
                $container['paging']['prevTitle'] = $prevKey;
                $container['paging']['prevUrl'] = "category/{$prevKey}";
            }

            if (isset($keys[$currentIndex + 1])) {
                $nextKey = $keys[$currentIndex + 1];
                $container['paging']['nextTitle'] = $nextKey;
                $container['paging']['nextUrl'] = "category/{$nextKey}";
            }

            if (0 === $currentIndex) {
                $containerList['category/'] = $container;
            }

            $containerList[$container['url']] = $container;
        }

        return $containerList;
    }
}
