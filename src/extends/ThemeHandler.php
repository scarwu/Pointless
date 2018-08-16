<?php
/**
 * Theme Handler
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class ThemeHandler
{
    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Get Type
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Init Data
     *
     * @param array
     */
    abstract public function initData($data);

    /**
     * Get Side Data
     *
     * @return array
     */
    public function getSideData()
    {
        return [];
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList()
    {
        return [];
    }
}
