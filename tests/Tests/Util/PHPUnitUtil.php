<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Util;

class PHPUnitUtil
{
    /**
     * @param list<mixed> $args
     */
    public static function callMethod(object $obj, string $name, array $args = []): mixed
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);

        return $method->invokeArgs($obj, $args);
    }
}
