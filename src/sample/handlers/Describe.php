<?php
/**
 * Describe Handler
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Handler;

use Pointless\Extend\ThemeHandler;

class Describe extends ThemeHandler
{
    public function __construct()
    {
        $this->type = 'describe';
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
        $containerList = [];

        foreach ($this->data['postBundle']['describe'] as $post) {
            $containerList[$post['url']] = $post;
        }

        return $containerList;
    }
}
