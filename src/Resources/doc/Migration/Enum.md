Migrations
===============

This bundle ships with a `AbstractEnumFixture` class which 
overrides the existing `AbstractEnumFixture` class from oro itself.
The main difference is, that this class also supports the `VersionedFixtureInterface`, which means
that you can add new options through this class.

New options are added with the next priority, options that already exist are left untouched.

```php
<?php

declare(strict_types=1);

namespace Acme\Bundle\CoreBundle\Migrations\Data\ORM;

use EHDev\BasicsBundle\Migrations\AbstractEnumFixture;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;

class LoadEnumData extends AbstractEnumFixture implements VersionedFixtureInterface
{
    public function getVersion(): string
    {
        return '1.1';
    }

    protected function getData(): array
    {
        return [
            'foo' => 'Foo',
            'bar' => 'Bar', // this is new
        ];
    }

    protected function getEnumCode(): string
    {
        return 'baz';
    }
}
```
