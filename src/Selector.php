<?php

namespace Palmtree\Html;

class Selector
{
    /** @var string */
    private $tag;
    /** @var string */
    private $id;
    /** @var array */
    private $classes = [];

    const PATTERN = '/(?<tags>[a-zA-Z]+)*(?<ids>#[^#\.]+)*(?<classes>(?:\.[^.#]+)*)/';

    public function __construct($selector = null)
    {
        if ($selector !== null) {
            $this->parse($selector);
        }
    }

    /**
     * @param string $selector
     */
    public function parse($selector)
    {
        if (\strpos($selector, '#') === false && \strpos($selector, '.') === false) {
            $this->setTag($selector);

            return;
        }

        $matches = [];
        \preg_match_all(static::PATTERN, $selector, $matches);

        if (isset($matches['tags'])) {
            $tags = \array_filter($matches['tags']);
            $tag  = \end($tags);
            $this->setTag($tag);
        }

        if (isset($matches['ids'])) {
            $ids = \array_filter($matches['ids']);
            $id  = \end($ids);
            $this->setId($id);
        }

        if (isset($matches['classes'])) {
            $classes = \array_filter($matches['classes']);

            foreach ($classes as $class) {
                $parts = \explode('.', \trim($class, '.'));

                foreach ($parts as $part) {
                    $this->addClass($part);
                }
            }
        }
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
     * @param string $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param array $classes
     *
     * @return self
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @param string $class
     */
    public function addClass($class)
    {
        $this->classes[] = $class;
    }
}
