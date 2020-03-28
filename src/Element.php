<?php

namespace Palmtree\Html;

use Palmtree\ArgParser\ArgParser;

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

    public static $singleLineElements = [
        'textarea',
    ];

    /**
     * Element constructor.
     *
     * @param array|string $args
     */
    public function __construct($args = [])
    {
        if (\is_array($args)) {
            (new ArgParser($args))->parseSetters($this);
        } elseif (\is_string($args)) {
            $selector = new Selector($args);

            $this->setTag($selector->getTag());
            $this->addAttribute('id', $selector->getId());

            foreach ($selector->getClasses() as $class) {
                $this->addClass($class);
            }
        }
    }

    /**
     * @param array $attributes
     * @param bool  $clear
     *
     * @return self
     */
    public function setAttributes(array $attributes, $clear = false)
    {
        if ($clear) {
            $this->attributes = [];
        }

        foreach ($attributes as $key => $value) {
            $this->addAttribute($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param bool   $value
     *
     * @return self
     */
    public function addAttribute($key, $value = true)
    {
        if ($value === null) {
            return $this;
        }
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function addDataAttribute($key, $value = '')
    {
        $this->addAttribute("data-$key", $value);

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = $this->attributes;
        $classes    = $this->getClasses();

        if ($classes) {
            if (isset($attributes['class'])) {
                $classes = array_unique(array_merge($classes, explode(' ', $attributes['class'])));
            }

            $attributes['class'] = implode(' ', $classes);
        }

        return $attributes;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getAttribute($key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    /**
     * @param string $key
     *
     * @return self
     */
    public function removeAttribute($key)
    {
        unset($this->attributes[$key]);

        return $this;
    }

    /**
     * @return string
     */
    public function getAttributesString()
    {
        $result = '';

        foreach ($this->getAttributes() as $key => $value) {
            if ($value === false) {
                continue;
            }

            $result .= " $key";
            if ($value !== true) {
                $result .= "=\"$value\"";
            }
        }

        return trim($result);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasClass($class)
    {
        return \in_array($class, $this->getClasses());
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param array|string $classes
     *
     * @return self
     */
    public function setClasses($classes)
    {
        if (!\is_array($classes)) {
            $classes = explode(' ', $classes);
        }
        $this->classes = $classes;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    public function addClass($class)
    {
        $this->classes[$class] = $class;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    public function removeClass($class)
    {
        unset($this->classes[$class]);

        return $this;
    }

    /**
     * @param string $tag
     *
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getInnerText()
    {
        return $this->innerText;
    }

    /**
     * @param array $elements
     *
     * @return self
     */
    public function addChildren(array $elements)
    {
        foreach ($elements as $element) {
            $this->addChild($element);
        }

        return $this;
    }

    /**
     * @param self $element
     *
     * @return self
     */
    public function addChild(self $element)
    {
        $this->children[] = $element;

        return $this;
    }

    /**
     * @param int $indent
     *
     * @return string
     */
    public function renderChildren($indent = 0)
    {
        if ($this->innerHtml === null) {
            $this->innerHtml = '';
            foreach ($this->children as $element) {
                $this->innerHtml .= "\n" . $element->render($indent + 1);
            }
        }

        return $this->innerHtml;
    }

    /**
     * @param int $indent
     *
     * @return string
     */
    public function render($indent = 0)
    {
        $tag = $this->getTag();

        $html = $this->getIndent($indent);

        $html .= "<$tag";

        $attributesString = $this->getAttributesString();

        if (!empty($attributesString)) {
            $html .= ' ' . $this->getAttributesString();
        }

        $innerText = $this->getInnerText();

        if (\in_array($tag, self::$voidElements)) {
            $html .= " />$innerText\n";
        } else {
            $html .= '>';

            if (!empty($innerText)) {
                $html .= "$innerText";
            }

            $innerHtml = $this->renderChildren($indent);

            $html .= $innerHtml;

            if (!empty($innerHtml) && empty($innerText) && !\in_array($tag, self::$singleLineElements)) {
                $html .= "\n";
                $html .= $this->getIndent($indent);
            }

            $html .= "</$tag>";
        }

        return $html;
    }

    private function getIndent($indent)
    {
        if ($this->getUseTab()) {
            return str_repeat("\t", $indent);
        } else {
            return str_repeat(' ', $indent * $this->getTabSize());
        }
    }

    /**
     * @param string $innerText
     *
     * @return self
     */
    public function setInnerText($innerText)
    {
        $this->innerText = $innerText;

        return $this;
    }

    /**
     * @param int $tabSize
     */
    public function setTabSize($tabSize)
    {
        $this->tabSize = $tabSize;
    }

    /**
     * @return int
     */
    public function getTabSize()
    {
        return $this->tabSize;
    }

    /**
     * @param bool $useTab
     */
    public function setUseTab($useTab)
    {
        $this->useTab = $useTab;
    }

    /**
     * @return bool
     */
    public function getUseTab()
    {
        return $this->useTab;
    }
}
