<?php

namespace Hejunjie\Tools\Cache;

use Hejunjie\Tools\Cache\Interfaces\DataSourceInterface;

abstract class CacheDecorator implements DataSourceInterface
{
    protected DataSourceInterface $wrapped;

    public function __construct(DataSourceInterface $wrapped)
    {
        $this->wrapped = $wrapped;
    }
}
