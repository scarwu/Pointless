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
    protected ?string $type = 'archive';
    /**
     * Init Data
     *
     * @param array
     */
    #[\Override]
    public function initData(array $data): void
    {
        $data['articleByArchive'] = [];

        foreach ($data['postBundle']['article'] as $post) {
            $archive = $post['year'];

            if (!isset($data['articleByArchive'][$archive])) {
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
    #[\Override]
    public function getSideData(): array
    {
        return $this->data['articleByArchive'];
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    #[\Override]
    public function getContainerDataList(): array
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

            if (isset($keys[$currentIndex - 1])) {
                $prevKey = $keys[$currentIndex - 1];
                $container['paging']['prevTitle'] = $prevKey;
                $container['paging']['prevUrl'] = "archive/{$prevKey}/";
            }

            if (isset($keys[$currentIndex + 1])) {
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
