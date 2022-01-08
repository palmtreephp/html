<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template-extends AbstractCollection<string>
 */
class ClassCollection extends AbstractCollection
{
    public function add(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->elements[$class] = $class;
        }

        return $this;
    }

    public function offsetSet($offset, $value): void
    {
        $this->elements[$value] = $value;
    }

    public function __toString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return ' class="' . implode(' ', $this->elements) . '"';
    }
}
