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
    protected ?string $path = null;

    /**
     * Run Extension
     *
     * @return string
     */
    final public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Run Extension
     *
     * @param array $data
     *
     * @return string
     */
    abstract public function render(array $data): string;
}
