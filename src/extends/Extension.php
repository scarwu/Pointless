<?php
/**
 * Extension
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class Extension
{
    /**
     * @var string
     */
    protected $path = null;

    /**
     * Run Extension
     *
     * @return string
     */
    final public function getPath() {
        return $this->path;
    }

    /**
     * Run Extension
     *
     * @param array $data
     *
     * @return string
     */
    abstract public function render($data);
}
