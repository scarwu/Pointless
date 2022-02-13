<?php
/**
 * Page Handler
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;

class Page extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'page';
    }

    /**
     * Init Data
     *
     * @param array
     */
    public function initData($data)
    {
        $data['articleByPage'] = [];
        $articleList = $data['postBundle']['article'];
        $quantity = $data['blogConfig']['post']['article']['quantity'];
        $totalIndex = ceil(count($articleList) / $quantity);

        for ($currentIndex = 1; $currentIndex <= $totalIndex; $currentIndex++) {
            $data['articleByPage'][$currentIndex] = array_slice($articleList, $quantity * ($currentIndex - 1), $quantity);
        }

        $this->data = $data;
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList()
    {
        $articleList = $this->data['articleByPage'];
        $keys = array_keys($articleList);
        $totalIndex = count($articleList);

        $containerList = [];

        foreach ($keys as $currentIndex => $key) {

            // Set Post
            $container = [];
            $container['url'] = "page/{$key}/";
            $container['list'] = $articleList[$key];

            // Set Paging
            $container['paging'] = [];
            $container['paging']['totalIndex'] = $totalIndex;
            $container['paging']['currentIndex'] = $currentIndex + 1;

            if (isset($keys[$currentIndex - 1])) {
                $prevKey = $keys[$currentIndex - 1];
                $container['paging']['prevTitle'] = $prevKey;
                $container['paging']['prevUrl'] = "page/{$prevKey}/";
            }

            if (isset($keys[$currentIndex + 1])) {
                $nextKey = $keys[$currentIndex + 1];
                $container['paging']['nextTitle'] = $nextKey;
                $container['paging']['nextUrl'] = "page/{$nextKey}/";
            }

            if (0 === $currentIndex) {
                $containerList['/'] = $container;
                $containerList['page/'] = $container;
            }

            $containerList[$container['url']] = $container;
        }

        return $containerList;
    }
}
