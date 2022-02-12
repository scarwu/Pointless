<?php
/**
 * Tag Handler
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;

class Tag extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'tag';
    }

    /**
     * Init Data
     *
     * @param array
     */
    public function initData($data)
    {
        $data['articleByTag'] = [];

        foreach ($data['postBundle']['article'] as $post) {
            foreach ($post['tags'] as $tag) {
                if (!isset($data['articleByTag'][$tag])) {
                    $data['articleByTag'][$tag] = [];
                }

                $data['articleByTag'][$tag][] = $post;
            }
        }

        ksort($data['articleByTag']);

        $this->data = $data;
    }

    /**
     * Get Side Data
     *
     * @return array
     */
    public function getSideData()
    {
        return $this->data['articleByTag'];
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList()
    {
        $articleList = $this->data['articleByTag'];
        $keys = array_keys($articleList);
        $totalIndex = count($articleList);

        $containerList = [];

        foreach ($keys as $currentIndex => $key) {

            // Set Post
            $container = [];
            $container['title'] = "Tag: {$key}";
            $container['url'] = "tag/{$key}/";
            $container['list'] = $articleList[$key];

            // Set Paging
            $container['paging'] = [];
            $container['paging']['totalIndex'] = $totalIndex;
            $container['paging']['currentIndex'] = $currentIndex + 1;

            if (isset($keys[$currentIndex - 1])) {
                $prevKey = $keys[$currentIndex - 1];
                $container['paging']['prevTitle'] = $prevKey;
                $container['paging']['prevUrl'] = "tag/{$prevKey}/";
            }

            if (isset($keys[$currentIndex + 1])) {
                $nextKey = $keys[$currentIndex + 1];
                $container['paging']['nextTitle'] = $nextKey;
                $container['paging']['nextUrl'] = "tag/{$nextKey}/";
            }

            if (0 === $currentIndex) {
                $containerList['tag/'] = $container;
            }

            $containerList[$container['url']] = $container;
        }

        return $containerList;
    }
}
