<?php
/**
 * Archive Handler
 *
 * @package     Pointless Theme - Unique
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;

class Archive extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'archive';
    }

    /**
     * Init Data
     *
     * @param array
     */
    public function initData($data)
    {
        $data['articleByArchive'] = [];

        foreach ($data['postBundle']['article'] as $post) {
            $archive = $post['year'];

            if (false === isset($data['articleByArchive'][$archive])) {
                $data['articleByArchive'][$archive] = [];
            }

            $data['articleByArchive'][$archive][] = $post;
        }

        krsort($data['articleByArchive']);

        $this->data = $data;
    }

    /**
     * Get Side Data
     *
     * @return array
     */
    public function getSideData()
    {
        return $this->data['articleByArchive'];
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList()
    {
        $articleList = $this->data['articleByArchive'];
        $keys = array_keys($articleList);
        $totalIndex = count($articleList);

        $containerList = [];

        foreach ($keys as $currentIndex => $key) {

            // Set Post
            $container = [];
            $container['title'] = "Archive: {$key}";
            $container['url'] = "archive/{$key}/";
            $container['list'] = $articleList[$key];

            // Set Paging
            $container['paging'] = [];
            $container['paging']['totalIndex'] = $totalIndex;
            $container['paging']['currentIndex'] = $currentIndex + 1;

            if (true === isset($keys[$currentIndex - 1])) {
                $prevKey = $keys[$currentIndex - 1];
                $container['paging']['prevTitle'] = $prevKey;
                $container['paging']['prevUrl'] = "archive/{$prevKey}/";
            }

            if (true === isset($keys[$currentIndex + 1])) {
                $nextKey = $keys[$currentIndex + 1];
                $container['paging']['nextTitle'] = $nextKey;
                $container['paging']['nextUrl'] = "archive/{$nextKey}/";
            }

            if (0 === $currentIndex) {
                $containerList['archive/'] = $container;
            }

            $containerList[$container['url']] = $container;
        }

        return $containerList;
    }
}
