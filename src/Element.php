<?php

declare(strict_types=1);

namespace Palmtree\Html;

use Palmtree\Html\Collection\AttributeCollection;
use Palmtree\Html\Collection\ClassCollection;

class Element
{
    /** @var array */
    public static $voidElements = [
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

    /** @var array */
    public static $singleLineElements = [
        'textarea',
    ];

    /** @var AttributeCollection */
    public $attributes;
    /** @var ClassCollection */
    public $classes;
    /** @var string */
    private $tag;
    /** @var string */
    private $innerText = '';
    /** @var string|null */
    private $innerHtml;
    /** @var Element[] */
    private $children = [];
    /** @var int */
    private $tabSize = 4;
    /** @var bool */
    private $useTab = false;

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

        $html .= $this->innerText;

        if (\in_array($this->tag, self::$voidElements, true)) {
            $html .= \PHP_EOL;

            return $html;
        }

        $innerHtml = $this->getInnerHtml($indentLevel);

        $html .= $innerHtml;

        if (!empty($innerHtml) && empty($this->innerText) && !\in_array($this->tag, self::$singleLineElements, true)) {
            $html .= \PHP_EOL . $indent;
        }

        $html .= $this->renderEnd();

        return $html;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

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

    public function getInnerHtml(int $indentLevel = 0): string
    {
        if ($this->innerHtml === null) {
            $this->innerHtml = '';
            foreach ($this->children as $element) {
                $this->innerHtml .= \PHP_EOL . $element->render($indentLevel + 1);
            }
        }

        return $this->innerHtml;
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
