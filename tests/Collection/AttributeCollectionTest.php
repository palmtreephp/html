<?php

declare(strict_types=1);

namespace Palmtree\Html\Test\Collection;

use Palmtree\Html\Collection\AttributeCollection;
use PHPUnit\Framework\TestCase;

class AttributeCollectionTest extends TestCase
{
    public function testSet(): void
    {
        $collection = new AttributeCollection();
        $result = $collection->set('id', 'test-id');

        $this->assertSame('test-id', $collection['id']);
        $this->assertSame($collection, $result); // Test fluent interface
    }

    public function testSetWithNullValue(): void
    {
        $collection = new AttributeCollection();
        $collection->set('disabled');

        $this->assertSame('', $collection['disabled']);
    }

    public function testAdd(): void
    {
        $collection = new AttributeCollection();
        $result = $collection->add([
            'id' => 'my-id',
            'title' => 'My Title',
            'data-value' => 'test',
        ]);

        $this->assertSame('my-id', $collection['id']);
        $this->assertSame('My Title', $collection['title']);
        $this->assertSame('test', $collection['data-value']);
        $this->assertSame($collection, $result); // Test fluent interface
    }

    public function testAddWithNullValues(): void
    {
        $collection = new AttributeCollection();
        $collection->add([
            'disabled' => null,
            'checked' => null,
        ]);

        $this->assertSame('', $collection['disabled']);
        $this->assertSame('', $collection['checked']);
    }

    public function testSetData(): void
    {
        $collection = new AttributeCollection();
        $result = $collection->setData('foo', 'bar');

        $this->assertSame('bar', $collection['data-foo']);
        $this->assertSame($collection, $result); // Test fluent interface
    }

    public function testSetDataWithEmptyValue(): void
    {
        $collection = new AttributeCollection();
        $collection->setData('test');

        $this->assertSame('', $collection['data-test']);
    }

    public function testRemoveData(): void
    {
        $collection = new AttributeCollection();
        $collection->setData('foo', 'bar');
        $result = $collection->removeData('foo');

        $this->assertFalse(isset($collection['data-foo']));
        $this->assertSame($collection, $result); // Test fluent interface
    }

    public function testRemoveDataNonExistent(): void
    {
        $collection = new AttributeCollection();
        $collection->removeData('non-existent');

        $this->assertFalse(isset($collection['data-non-existent']));
    }

    public function testValues(): void
    {
        $collection = new AttributeCollection();
        $collection->add([
            'id' => 'my-id',
            'title' => 'My Title',
        ]);

        $values = $collection->values();

        $this->assertSame(['my-id', 'My Title'], $values);
    }

    public function testValuesEmpty(): void
    {
        $collection = new AttributeCollection();

        $this->assertSame([], $collection->values());
    }

    public function testClear(): void
    {
        $collection = new AttributeCollection();
        $collection->add([
            'id' => 'my-id',
            'title' => 'My Title',
        ]);

        $collection->clear();

        $this->assertCount(0, $collection);
    }

    public function testToStringEmpty(): void
    {
        $collection = new AttributeCollection();

        $this->assertSame('', (string)$collection);
    }

    public function testToStringWithAttributes(): void
    {
        $collection = new AttributeCollection();
        $collection->set('id', 'test-id');
        $collection->set('title', 'My Title');
        $collection->set('disabled');

        $string = (string)$collection;

        $this->assertStringContainsString('id="test-id"', $string);
        $this->assertStringContainsString('title="My Title"', $string);
        $this->assertStringContainsString('disabled', $string);
    }

    public function testToStringWithoutValue(): void
    {
        $collection = new AttributeCollection();
        $collection->set('checked');

        $this->assertSame(' checked', (string)$collection);
    }

    public function testToStringStartsWithSpace(): void
    {
        $collection = new AttributeCollection();
        $collection->set('id', 'test');

        $this->assertStringStartsWith(' ', (string)$collection);
    }

    public function testFluentInterface(): void
    {
        $collection = new AttributeCollection();
        $result = $collection
            ->set('id', 'my-id')
            ->set('title', 'My Title')
            ->setData('foo', 'bar');

        $this->assertSame($collection, $result);
        $this->assertSame('my-id', $collection['id']);
        $this->assertSame('My Title', $collection['title']);
        $this->assertSame('bar', $collection['data-foo']);
    }

    public function testArrayAccess(): void
    {
        $collection = new AttributeCollection();
        $collection['id'] = 'test-id';

        $this->assertSame('test-id', $collection['id']);
    }

    public function testCount(): void
    {
        $collection = new AttributeCollection();
        $this->assertCount(0, $collection);

        $collection->set('id', 'test');
        $this->assertCount(1, $collection);

        $collection->set('title', 'test');
        $this->assertCount(2, $collection);
    }

    public function testIteration(): void
    {
        $collection = new AttributeCollection();
        $collection->add([
            'id' => 'test-id',
            'title' => 'My Title',
        ]);

        $items = [];
        foreach ($collection as $key => $value) {
            $items[$key] = $value;
        }

        $this->assertSame('test-id', $items['id']);
        $this->assertSame('My Title', $items['title']);
    }
}
