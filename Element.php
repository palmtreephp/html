<?php

namespace Palmtree\Html;

use Palmtree\ArgParser\ArgParser;

class Element
{
    protected $tag;
    protected $innerText = '';
    protected $innerHtml;
    /** @var Element[] */
    protected $childElements = [];
    protected $attributes = [];
    protected $classes = [];

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

    public function __construct($args = [])
    {
        if (is_array($args)) {
            (new ArgParser($args))->parseSetters($this);
        } elseif (is_string($args)) {
            $selector = new Selector($args);

            $this->setTag($selector->getTag());
            $this->addAttribute('id', $selector->getId());

            foreach ($selector->getClasses() as $class) {
                $this->addClass($class);
            }
        }
    }

    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->addAttribute($key, $value);
        }

        return $this;
    }

    public function addAttribute($key, $value = true)
    {
        if ($value === null) {
            return $this;
        }
        $this->attributes[$key] = $value;

        return $this;
    }

    public function addDataAttribute($key, $value = '')
    {
        $this->attributes['data-' . $key] = $value;

        return $this;
    }

    public function getAttributes()
    {
        return ['class' => implode(' ', $this->classes)] + $this->attributes;
    }

    public function getAttribute($key)
    {
        return (isset($this->attributes[$key])) ? $this->attributes[$key] : null;
    }

    public function removeAttribute($key)
    {
        unset($this->attributes[$key]);

        return $this;
    }

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

    public function getClasses()
    {
        return $this->classes;
    }

    public function setClasses($classes)
    {
        if (!is_array($classes)) {
            $classes = explode(' ', $classes);
        }
        $this->classes = $classes;

        return $this;
    }

    public function addClass($class)
    {
        $this->classes[$class] = $class;

        return $this;
    }

    public function removeClass($class)
    {
        unset($this->classes[$class]);

        return $this;
    }

    /**
     * @param mixed $tag
     *
     * @return Element
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    public function getInnerText()
    {
        return $this->innerText;
    }

    public function addChildren(array $elements)
    {
        foreach ($elements as $element) {
            $this->addChild($element);
        }

        return $this;
    }

    public function addChild(Element $element)
    {
        $this->childElements[] = $element;

        return $this;
    }

    public function renderChildren($indent = 0)
    {
        if ($this->innerHtml === null) {
            $this->innerHtml = '';
            foreach ($this->childElements as $element) {
                $this->innerHtml .= "\n" . $element->render($indent + 1);
            }
        }

        return $this->innerHtml;
    }

    public function render($indent = 0)
    {
        $tag = $this->getTag();

        $html = str_repeat("\t", $indent);

        $html .= "<$tag";

        $attributesString = $this->getAttributesString();

        if (!empty($attributesString)) {
            $html .= ' ' . $this->getAttributesString();
        }

        $innerText = $this->getInnerText();

        if (in_array($tag, self::$voidElements)) {
            $html .= " />$innerText\n";
        } else {
            $html .= ">";

            if (!empty($innerText)) {
                $html .= "$innerText";
            }

            $innerHtml = $this->renderChildren($indent);

            $html .= $innerHtml;

            if (!empty($innerHtml) && empty($innerText) && !in_array($tag, self::$singleLineElements)) {
                $html .= "\n";
                $html .= str_repeat("\t", $indent);
            }

            $html .= "</$tag>";
        }

        return $html;
    }

    /**
     * @param string $innerText
     *
     * @return Element
     */
    public function setInnerText($innerText)
    {
        $this->innerText = $innerText;

        return $this;
    }
}
