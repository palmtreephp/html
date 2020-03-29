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

    private const PATTERN = '/([a-zA-Z]+)*(#[^#\.]+)*((?:\.[^.#]+)*)/';

    public function __construct(string $selector)
    {
        $this->parse($selector);
    }

    private function parse(string $selector): void
    {
        if (strpos($selector, '#') === false && strpos($selector, '.') === false) {
            $this->tag = $selector;

            return;
        }

        preg_match_all(self::PATTERN, $selector, $matches);

        if (empty($matches[1][0])) {
            throw new \LogicException('Selector must contain at least a tag');
        }

        $this->tag = $matches[1][0];

        if (!empty($matches[2][0])) {
            $this->id = ltrim($matches[2][0], '#');
        }

        if (!empty($matches[3][0])) {
            foreach (explode('.', trim($matches[3][0], '.')) as $class) {
                $this->classes[] = $class;
            }
        }
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }
}
