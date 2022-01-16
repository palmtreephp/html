<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template-extends \ArrayObject<int, string>
 */
class ClassCollection extends \ArrayObject
{
    /**
     * @param string ...$elements
     */
    public function add(...$elements): self
    {
        foreach ($elements as $value) {
            /** @psalm-suppress NullArgument */
            $this[] = $value;
        }

        return $this;
    }

    /**
     * @return list<string>
     */
    public function values(): array
    {
        return array_values($this->getArrayCopy());
    }

    public function __toString(): string
    {
        if ($this->count() === 0) {
            return '';
        }

        return ' class="' . implode(' ', $this->getArrayCopy()) . '"';
    }
}
