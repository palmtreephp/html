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

    public function testInnerTextIsEscaped(): void
    {
        $div = Element::create('div')->setInnerText('<script>alert("x")</script> & \'y\'');

        $this->assertSame('<script>alert("x")</script> & \'y\'', $div->getInnerText());
        $this->assertSame('<div>&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt; &amp; &apos;y&apos;</div>', $div->render());
    }

    public function testTextareaCannotBeClosedByInnerText(): void
    {
        $textarea = Element::create('textarea')->setInnerText('</textarea><img src=x onerror=alert(1)>');

        $this->assertSame('<textarea>&lt;/textarea&gt;&lt;img src=x onerror=alert(1)&gt;</textarea>', $textarea->render());
    }

    public function testInnerHtmlIsNotEscaped(): void
    {
        $div = Element::create('div')->setInnerHtml('<strong>Hello</strong>');

        $this->assertSame('<div><strong>Hello</strong></div>', $div->render());
    }

    public function testInnerHtmlOrder(): void
    {
        $div = Element::create('div')
            ->setInnerText('a & b')
            ->setInnerHtml('<br>')
            ->addChild(Element::create('span'));

        $this->assertSame("a &amp; b<br>\n    <span></span>", $div->getInnerHtml());
        $this->assertSame("<div>a &amp; b<br>\n    <span></span></div>", $div->render());
    }

    public function testChildrenAddedAfterRenderAreRendered(): void
    {
        $div = Element::create('div')->addChild(Element::create('span'));
        $div->render();
        $div->addChild(Element::create('em'));

        $this->assertSame("<div>\n    <span></span>\n    <em></em>\n</div>", $div->render());
    }

    public function testNestedIndentation(): void
    {
        $div = Element::create('div')->addChild(Element::create('ul')->addChild(Element::create('li')));

        $this->assertSame("<div>\n    <ul>\n        <li></li>\n    </ul>\n</div>", $div->render());
    }

    public function testAttributeValuesAreEscaped(): void
    {
        $input = Element::create('input');
        $input->attributes['value'] = '"><script>alert(1)</script>';
        $input->classes[] = 'foo" onclick="alert(1)';

        $html = $input->render();

        $this->assertSame('<input class="foo&quot; onclick=&quot;alert(1)" value="&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;">' . \PHP_EOL, $html);

        $document = new \DOMDocument();
        $document->loadHTML($html);
        $node = $document->getElementsByTagName('input')->item(0);

        $this->assertInstanceOf(\DOMElement::class, $node);
        $this->assertSame('"><script>alert(1)</script>', $node->getAttribute('value'));
        $this->assertFalse($node->hasAttribute('onclick'));
        $this->assertSame(0, $document->getElementsByTagName('script')->length);
    }

    public function testInvalidTagNameThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Element('div'))->setTag('div onclick=alert(1)');
    }

    public function testDefaultGetters(): void
    {
        $div = new Element('div');

        $this->assertSame('div', $div->getTag());
        $this->assertSame(4, $div->getTabSize());
        $this->assertFalse($div->getUseTab());
    }
}
