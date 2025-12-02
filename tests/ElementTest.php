<?php

declare(strict_types=1);

namespace Palmtree\Html\Test;

use Palmtree\Html\Element;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testRender(): void
    {
        $element = Element::create('div')->addChild(Element::create('span'));

        $this->assertSame("<div>\n    <span></span>\n</div>", $element->render());
    }

    public function testTabSize(): void
    {
        $element = Element::create('div')
            ->addChild(Element::create('span'))
            ->setTabSize(2);

        $this->assertSame("<div>\n  <span></span>\n</div>", $element->render());
    }

    public function testUseTab(): void
    {
        $element = Element::create('div')
            ->addChild(Element::create('span'))
            ->setUseTab(true);

        $this->assertSame("<div>\n\t<span></span>\n</div>", $element->render());
    }

    public function testAddClass(): void
    {
        $element = Element::create('div');
        $element->classes->add('foo', 'bar');

        $this->assertContains('foo', $element->classes);
        $this->assertContains('bar', $element->classes);
    }

    public function testAttributes(): void
    {
        $element = Element::create('input#foo.bar.baz');

        $element->attributes
            ->setData('foo', 'bar')
            ->set('type', 'checkbox')
            ->set('checked')
        ;

        $html = $element->render();

        $document = new \DOMDocument();
        $document->loadHTML($html);
        $node = $document->getElementsByTagName('body')->item(0)?->childNodes->item(0);

        $this->assertInstanceOf(\DOMElement::class, $node);
        $this->assertSame('input', $node->tagName);
        $this->assertSame('checkbox', $node->getAttribute('type'));
        $this->assertSame('foo', $node->getAttribute('id'));
        $this->assertSame('bar baz', $node->getAttribute('class'));
        $this->assertSame('bar', $node->getAttribute('data-foo'));
        $this->assertTrue($node->hasAttribute('checked'));

        $this->assertSame('<input class="bar baz" id="foo" data-foo="bar" type="checkbox" checked>' . \PHP_EOL, $html);
    }

    public function testAttributeSelector(): void
    {
        $element = Element::create('input[type=checkbox][checked]');

        $this->assertSame('<input type="checkbox" checked>' . \PHP_EOL, $element->render());
    }

    public function testInnerText(): void
    {
        $div = Element::create('div')->setInnerText('Hello, World!');

        $this->assertSame('Hello, World!', $div->getInnerText());

        $this->assertSame('<div>Hello, World!</div>', $div->render());
    }

    public function testDefaultGetters(): void
    {
        $div = new Element('div');

        $this->assertSame('div', $div->getTag());
        $this->assertSame(4, $div->getTabSize());
        $this->assertFalse($div->getUseTab());
    }
}
