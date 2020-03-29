<?php

namespace Palmtree\Html;

class Element
{
    /** @var string */
    private $tag;
    /** @var string */
    private $innerText = '';
    /** @var string */
    private $innerHtml;
    /** @var Element[] */
    private $children = [];
    /** @var array */
    private $attributes = [];
    /** @var array */
    private $classes = [];
    /** @var int */
    private $tabSize = 4;
    /** @var bool */
    private $useTab = false;
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

    public function __construct(?string $selector = null)
    {
        if ($selector) {
            $selector = new Selector($selector);

            $this->setTag($selector->getTag());
            $this->setAttributes($selector->getAttributes());

            if ($id = $selector->getId()) {
                $this->addAttribute('id', $id);
            }

            $this->addClass(...$selector->getClasses());
        }
    }

    public static function create(?string $selector = null): self
    {
        return new self($selector);
    }

    public function render(int $indentLevel = 0): string
    {
        $html = $indent = $this->getIndent($indentLevel);

        $html .= "<$this->tag";

        if ($attributesString = $this->getAttributesString()) {
            $html .= " $attributesString";
        }

        if (\in_array($this->tag, self::$voidElements)) {
            $html .= ">$this->innerText" . PHP_EOL;

            return $html;
        }

        $html .= ">$this->innerText";

        $innerHtml = $this->getInnerHtml($indentLevel);

        $html .= $innerHtml;

        if (!empty($innerHtml) && empty($this->innerText) && !\in_array($this->tag, self::$singleLineElements)) {
            $html .= PHP_EOL . $indent;
        }

        $html .= "</$this->tag>";

        return $html;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = [];

        foreach ($attributes as $key => $value) {
            $this->addAttribute($key, $value);
        }

        return $this;
    }

    public function addAttribute(string $key, ?string $value = ''): self
    {
        if ($key === 'class') {
            throw new \InvalidArgumentException('Use ' . __CLASS__ . '::addClass or ' . __CLASS__ . 'setClasses to manipulate \'class\' attribute');
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function addDataAttribute(string $key, string $value = ''): self
    {
        $this->addAttribute("data-$key", $value);

        return $this;
    }

    public function getAttributes(): array
    {
        $attributes = $this->attributes;

        if ($this->classes) {
            $attributes['class'] = implode(' ', $this->classes);
        }

        return $attributes;
    }

    public function getAttribute(string $key): ?string
    {
        return $this->attributes[$key] ?? null;
    }

    public function removeAttribute(string $key): self
    {
        unset($this->attributes[$key]);

        return $this;
    }

    public function getAttributesString(): string
    {
        $result = '';

        foreach ($this->getAttributes() as $key => $value) {
            $result .= " $key";
            if (!empty($value)) {
                $result .= "=\"$value\"";
            }
        }

        return trim($result);
    }

    public function hasClass(string $class): bool
    {
        return isset($this->classes[$class]);
    }

    public function getClasses(): array
    {
        return $this->classes;
    }

    public function addClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->classes[$class] = $class;
        }

        return $this;
    }

    public function removeClass(string $class): self
    {
        unset($this->classes[$class]);

        return $this;
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
                $this->innerHtml .= PHP_EOL . $element->render($indentLevel + 1);
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
