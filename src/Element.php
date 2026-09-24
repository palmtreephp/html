<?php

declare(strict_types=1);

namespace Palmtree\Html;

use Palmtree\Html\Collection\AttributeCollection;
use Palmtree\Html\Collection\ClassCollection;

class Element
{
    /** @var list<string> */
    public static array $voidElements = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    /** @var list<string> */
    public static array $singleLineElements = [
        'textarea',
    ];

    public AttributeCollection $attributes;
    public ClassCollection $classes;
    private string $tag;
    private string $innerText = '';
    private string $innerHtml = '';
    /** @var list<Element> */
    private array $children = [];
    private int $tabSize = 4;
    private bool $useTab = false;

    public function __construct(?string $selectorString = null)
    {
        $this->attributes = new AttributeCollection();
        $this->classes = new ClassCollection();

        if ($selectorString) {
            $selector = new Selector($selectorString);

            $this->setTag($selector->getTag());

            foreach ($selector->attributes as $key => $value) {
                $this->attributes->set($key, $value);
            }

            if ($id = $selector->getId()) {
                $this->attributes->set('id', $id);
            }

            $this->classes->add(...$selector->classes->values());
        }
    }

    public static function create(?string $selector = null): self
    {
        return new self($selector);
    }

    public function renderStart(int $indentLevel = 0): string
    {
        $indent = $this->getIndent($indentLevel);

        $html = "$indent<$this->tag";
        $html .= $this->classes;
        $html .= $this->attributes;

        $html .= '>';

        return $html;
    }

    public function renderEnd(): string
    {
        return "</$this->tag>";
    }

    public function render(int $indentLevel = 0): string
    {
        $indent = $this->getIndent($indentLevel);

        $html = $this->renderStart($indentLevel);

        if (\in_array($this->tag, self::$voidElements, true)) {
            $html .= \PHP_EOL;

            return $html;
        }

        $html .= $this->getInnerHtml($indentLevel);

        if ($this->children && $this->innerText === '' && $this->innerHtml === '' && !\in_array($this->tag, self::$singleLineElements, true)) {
            $html .= \PHP_EOL . $indent;
        }

        $html .= $this->renderEnd();

        return $html;
    }

    /**
     * @throws \InvalidArgumentException If the tag name is not a valid HTML tag name.
     */
    public function setTag(string $tag): self
    {
        Escaper::assertValidTagName($tag);

        $this->tag = $tag;

        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Sets the element's text content. It is HTML-escaped when rendered.
     */
    public function setInnerText(string $innerText): self
    {
        $this->innerText = $innerText;

        return $this;
    }

    public function getInnerText(): string
    {
        return $this->innerText;
    }

    public function addChild(self ...$elements): self
    {
        foreach ($elements as $element) {
            $this->children[] = $element;
        }

        return $this;
    }

    /**
     * Sets raw HTML content, rendered after the inner text and before any children. It is NOT escaped,
     * so it must never contain untrusted input.
     */
    public function setInnerHtml(string $innerHtml): self
    {
        $this->innerHtml = $innerHtml;

        return $this;
    }

    /**
     * Returns the rendered content of the element: its escaped inner text, raw inner HTML and rendered children.
     */
    public function getInnerHtml(int $indentLevel = 0): string
    {
        $html = Escaper::escape($this->innerText) . $this->innerHtml;

        foreach ($this->children as $element) {
            $html .= \PHP_EOL . $element->render($indentLevel + 1);
        }

        return $html;
    }

    public function setTabSize(int $tabSize): self
    {
        $this->tabSize = $tabSize;

        foreach ($this->children as $child) {
            $child->setTabSize($tabSize);
        }

        return $this;
    }

    public function getTabSize(): int
    {
        return $this->tabSize;
    }

    public function setUseTab(bool $useTab): self
    {
        $this->useTab = $useTab;

        foreach ($this->children as $child) {
            $child->setUseTab($useTab);
        }

        return $this;
    }

    public function getUseTab(): bool
    {
        return $this->useTab;
    }

    private function getIndent(int $level): string
    {
        if ($this->useTab) {
            return str_repeat("\t", $level);
        }

        return str_repeat(' ', $level * $this->tabSize);
    }
}
