<?php

declare(strict_types=1);

namespace EHDev\BasicsBundle\Tests\Util;

class PHPUnitUtil
{
    public static function callMethod(object $obj, string $name, array $args = []): mixed
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }
}
