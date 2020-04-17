<?php

namespace Palmtree\Html\Test;

use Palmtree\Html\Element;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testRender()
    {
        $element = Element::create('div')->addChild(Element::create('span'));

        $this->assertSame("<div>\n    <span></span>\n</div>", $element->render());
    }

    public function testTabSize()
    {
        $element = Element::create('div')
                          ->addChild(Element::create('span'))
                          ->setTabSize(2);

        $this->assertSame("<div>\n  <span></span>\n</div>", $element->render());
    }

    public function testUseTab()
    {
        $element = Element::create('div')
                          ->addChild(Element::create('span'))
                          ->setUseTab(true);

        $this->assertSame("<div>\n\t<span></span>\n</div>", $element->render());
    }

    public function testAddClass()
    {
        $element = Element::create('div');
        $element->classes->add('foo', 'bar');

        $this->assertTrue($element->classes->has('foo'));
        $this->assertTrue($element->classes->has('bar'));
    }

    public function testAttributes()
    {
        $element = Element::create('input#foo.bar.baz');

        $element->attributes->setData('foo', 'bar')
                            ->set('type', 'checkbox')
                            ->set('checked', true);

        $document = new \DOMDocument();
        $document->loadHTML($element->render());
        $node = $document->getElementsByTagName('body')->item(0)->childNodes->item(0);

        $this->assertSame('input', $node->tagName);
        $this->assertSame('checkbox', $node->getAttribute('type'));
        $this->assertSame('foo', $node->getAttribute('id'));
        $this->assertSame('bar baz', $node->getAttribute('class'));
        $this->assertSame('bar', $node->getAttribute('data-foo'));
        $this->assertSame('1', $node->getAttribute('checked'));
    }

    public function testAttributeSelector()
    {
        $element = Element::create('input[type=checkbox][checked]');

        $this->assertSame('<input type="checkbox" checked>' . PHP_EOL, $element->render());
    }

    public function testInnerText()
    {
        $div = Element::create('div')->setInnerText('Hello, World!');

        $this->assertSame('Hello, World!', $div->getInnerText());

        $this->assertSame('<div>Hello, World!</div>', $div->render());
    }

    public function testDefaultGetters()
    {
        $div = new Element('div');

        $this->assertSame('div', $div->getTag());
        $this->assertSame(4, $div->getTabSize());
        $this->assertFalse($div->getUseTab());
    }
}
