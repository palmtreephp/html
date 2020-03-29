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
}
