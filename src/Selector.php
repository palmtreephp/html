<?php

namespace Palmtree\Html;

use Palmtree\Html\Collection\AttributeCollection;
use Palmtree\Html\Collection\ClassCollection;
use Palmtree\Html\Exception\BadSelectorException;

class Selector
{
    /** @var string */
    private $tag;
    /** @var string */
    private $id;
    /** @var ClassCollection */
    public $classes;
    /** @var AttributeCollection */
    public $attributes;

    private const PATTERN           = '/([a-zA-Z]+)*((?:\[[^\]]+\])*)(#[^#\.]+)*((?:\.[^.#]+)*)/';
    private const TAG_MATCHES       = 1;
    private const ATTRIBUTE_MATCHES = 2;
    private const ID_MATCHES        = 3;
    private const CLASS_MATCHES     = 4;

    public function __construct(string $selector)
    {
        $this->classes    = new ClassCollection();
        $this->attributes = new AttributeCollection();
        $this->parse($selector);
    }

    private function parse(string $selector): void
    {
        if (strpos($selector, '[') === false && strpos($selector, '#') === false && strpos($selector, '.') === false) {
            $this->tag = $selector;

            return;
        }

        preg_match_all(self::PATTERN, $selector, $matches);

        if (empty($matches[self::TAG_MATCHES][0])) {
            throw new BadSelectorException('Selector must contain at least a tag');
        }

        $this->tag = $matches[self::TAG_MATCHES][0];

        if (!empty($matches[self::ATTRIBUTE_MATCHES][0])) {
            foreach (explode(']', rtrim($matches[self::ATTRIBUTE_MATCHES][0], ']')) as $attribute) {
                $attribute = ltrim($attribute, '[');
                $parts     = explode('=', $attribute);
                if (isset($parts[1])) {
                    $parts[1] = trim($parts[1], '"\'');
                }

                if ($parts[0] === 'id') {
                    $this->id = $parts[1];
                } elseif ($parts[0] === 'class') {
                    $this->classes->add(...explode(' ', $parts[1]));
                } else {
                    $this->attributes->set($parts[0], $parts[1] ?? null);
                }
            }
        }

        if (!empty($matches[self::ID_MATCHES][0])) {
            $this->id = ltrim($matches[self::ID_MATCHES][0], '#');
        }

        if (!empty($matches[self::CLASS_MATCHES][0])) {
            $this->classes->add(...explode('.', trim($matches[self::CLASS_MATCHES][0], '.')));
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
}
