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
    protected ?string $type = null;
    protected array $data = [];

    /**
     * Get Type
     *
     * @return string
     */
    final public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Init Data
     *
     * @param array
     */
    abstract public function initData(array $data): void;

    /**
     * Get Side Data
     *
     * @return array
     */
    public function getSideData(): array
    {
        return [];
    }

    /**
     * Get Container Data List
     *
     * @return array
     */
    public function getContainerDataList(): array
    {
        return [];
    }
}
