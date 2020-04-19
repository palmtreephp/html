<?php

namespace Palmtree\Html\Collection;

class ClassCollection extends AbstractCollection
{
    public function add(string ...$classes): self
    {
        foreach ($classes as $class) {
            $this->set($class, $class);
        }

        return $this;
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($value, $value);
    }

    public function __toString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        return ' class="' . implode(' ', $this->elements) . '"';
    }
}
