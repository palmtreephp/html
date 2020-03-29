<?php

namespace Palmtree\Html\Test;

use Palmtree\Html\Selector;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    public function testTagIdAndClasses()
    {
        $selector = new Selector('div#foo.bar.baz');

        $this->assertSame('div', $selector->getTag());
        $this->assertSame('foo', $selector->getId());
        $this->assertSame(['bar', 'baz'], $selector->getClasses());
    }

    public function testAttribute()
    {
        $selector = new Selector('input[type=checkbox][checked]');

        $this->assertArrayHasKey('type', $selector->getAttributes());
        $this->assertArrayHasKey('checked', $selector->getAttributes());
    }
}
