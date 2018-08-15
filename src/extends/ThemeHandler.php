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
    use RenderTools;

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
     * Get Data
     *
     * @return string
     */
    final public function getData()
    {
        return $this->data;
    }

    /**
     * Init Data
     *
     * @param array
     */
    abstract public function initData($postBundle);

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
     * Get Container Data
     *
     * @return array
     */
    public function getContainerData()
    {
        return [];
    }
}
