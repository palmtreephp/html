<?php

namespace Palmtree\Html\Test;

use Palmtree\Html\Exception\BadSelectorException;
use Palmtree\Html\Selector;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    public function testTagIdAndClasses()
    {
        $selector = new Selector('div#foo.bar.baz');

        $this->assertSame('div', $selector->getTag());
        $this->assertSame('foo', $selector->getId());
        $this->assertSame(['bar', 'baz'], $selector->classes->values());
    }

    public function testAttribute()
    {
        $selector = new Selector('input[type=checkbox][checked]');

        $this->assertArrayHasKey('type', $selector->attributes);
        $this->assertArrayHasKey('checked', $selector->attributes);
    }

    public function testHrefAttribute()
    {
        $selector = new Selector('a[href=https://example.org][id="foo"]');

        $this->assertSame('https://example.org', $selector->attributes['href']);
        $this->assertSame('foo', $selector->getId());
    }

    public function testClassesViaAttributeAndSelector()
    {
        $selector = new Selector('div[class="foo bar"].baz');

        $this->assertSame(['foo', 'bar', 'baz'], $selector->classes->values());
    }

    public function testBadSelector()
    {
        $this->expectException(BadSelectorException::class);

        new Selector('#foo');
    }
}
