Migrations
===============

This bundle ships with a `AbstractEnumFixture` class which 
overrides the existing `AbstractEnumFixture` class from oro itself.
The main difference is, that this class also supports the `VersionedFixtureInterface`, which means
that you can add new options through this class.

```
<?php

namespace Acme\Bundle\CoreBundle\Migrations\Data\ORM;

use Ehdev\OroBasicsBundle\Migrations\AbstractEnumFixture;
use Oro\Bundle\MigrationBundle\Fixture\VersionedFixtureInterface;

class LoadEnumData extends AbstractEnumFixture
    implements VersionedFixtureInterface
{
    public function getVersion()
    {
        return '1.1';
    }

    protected function getData()
    {
        return [
            'foo' => 'Foo',
            'bar' => 'Bar', // this is new
        ];
    }

    protected function getEnumCode()
    {
        return 'baz';
    }
}
```