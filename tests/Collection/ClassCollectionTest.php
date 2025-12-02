<?php

declare(strict_types=1);

namespace Palmtree\Html\Test\Collection;

use Palmtree\Html\Collection\ClassCollection;
use PHPUnit\Framework\TestCase;

class ClassCollectionTest extends TestCase
{
    public function testAdd(): void
    {
        $collection = new ClassCollection();
        $result = $collection->add('foo', 'bar', 'baz');

        $this->assertTrue($collection->contains('foo'));
        $this->assertTrue($collection->contains('bar'));
        $this->assertTrue($collection->contains('baz'));
        $this->assertSame($collection, $result); // Test fluent interface
    }

    public function testAddSingleClass(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo');

        $this->assertCount(1, $collection);
        $this->assertTrue($collection->contains('foo'));
    }

    public function testAddMultipleTimes(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo');
        $collection->add('bar');

        $this->assertCount(2, $collection);
        $this->assertTrue($collection->contains('foo'));
        $this->assertTrue($collection->contains('bar'));
    }

    public function testContains(): void
    {
        $collection = new ClassCollection();
        $collection->add('active', 'highlight');

        $this->assertTrue($collection->contains('active'));
        $this->assertTrue($collection->contains('highlight'));
        $this->assertFalse($collection->contains('inactive'));
    }

    public function testContainsIsCaseSensitive(): void
    {
        $collection = new ClassCollection();
        $collection->add('Active');

        $this->assertTrue($collection->contains('Active'));
        $this->assertFalse($collection->contains('active'));
    }

    public function testRemove(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar', 'baz');

        $result = $collection->remove('bar');

        $this->assertTrue($result);
        $this->assertFalse($collection->contains('bar'));
        $this->assertTrue($collection->contains('foo'));
        $this->assertTrue($collection->contains('baz'));
        $this->assertCount(2, $collection);
    }

    public function testRemoveNonExistent(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar');

        $result = $collection->remove('nonexistent');

        $this->assertFalse($result);
        $this->assertCount(2, $collection);
    }

    public function testRemoveFromEmpty(): void
    {
        $collection = new ClassCollection();

        $result = $collection->remove('foo');

        $this->assertFalse($result);
    }

    public function testValues(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar', 'baz');

        $values = $collection->values();

        $this->assertSame(['foo', 'bar', 'baz'], $values);
    }

    public function testValuesEmpty(): void
    {
        $collection = new ClassCollection();

        $this->assertSame([], $collection->values());
    }

    public function testValuesPrunesGaps(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar', 'baz');
        $collection->remove('bar');

        $values = $collection->values();

        // Should be reindexed, no gaps
        $this->assertSame(['foo', 'baz'], $values);
    }

    public function testClear(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar', 'baz');

        $collection->clear();

        $this->assertCount(0, $collection);
        $this->assertFalse($collection->contains('foo'));
    }

    public function testToStringEmpty(): void
    {
        $collection = new ClassCollection();

        $this->assertSame('', (string)$collection);
    }

    public function testToStringWithClasses(): void
    {
        $collection = new ClassCollection();
        $collection->add('active', 'highlight', 'special');

        $string = (string)$collection;

        $this->assertSame(' class="active highlight special"', $string);
    }

    public function testToStringSingleClass(): void
    {
        $collection = new ClassCollection();
        $collection->add('active');

        $this->assertSame(' class="active"', (string)$collection);
    }

    public function testToStringStartsWithSpace(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo');

        $this->assertStringStartsWith(' ', (string)$collection);
    }

    public function testFluentInterface(): void
    {
        $collection = new ClassCollection();
        $result = $collection->add('foo')->add('bar')->add('baz');

        $this->assertSame($collection, $result);
        $this->assertCount(3, $collection);
    }

    public function testArrayAccess(): void
    {
        $collection = new ClassCollection();
        $collection[] = 'foo';
        $collection[] = 'bar';

        $this->assertSame('foo', $collection[0]);
        $this->assertSame('bar', $collection[1]);
    }

    public function testCount(): void
    {
        $collection = new ClassCollection();
        $this->assertCount(0, $collection);

        $collection->add('foo');
        $this->assertCount(1, $collection);

        $collection->add('bar', 'baz');
        $this->assertCount(3, $collection);
    }

    public function testIteration(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar', 'baz');

        $items = [];
        foreach ($collection as $key => $value) {
            $items[$key] = $value;
        }

        $this->assertSame(['foo', 'bar', 'baz'], array_values($items));
    }

    public function testRemoveAndReAdd(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo', 'bar', 'baz');
        $collection->remove('bar');
        $collection->add('bar');

        $this->assertCount(3, $collection);
        $this->assertTrue($collection->contains('foo'));
        $this->assertTrue($collection->contains('bar'));
        $this->assertTrue($collection->contains('baz'));
    }

    public function testAddDuplicates(): void
    {
        $collection = new ClassCollection();
        $collection->add('foo');
        $collection->add('foo');

        // ArrayObject allows duplicates, so we should have 2 entries
        $this->assertCount(2, $collection);
    }
}
