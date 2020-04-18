<?php

namespace Palmtree\Html\Collection;

class AttributeCollection extends AbstractCollection
{
    public function setData(string $key, string $value = ''): self
    {
        $this->set("data-$key", $value);

        return $this;
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function __toString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $bits = [];
        foreach ($this->elements as $key => $value) {
            $bits[] = $key . (empty($value) ? '' : "=\"$value\"");
        }

        return ' ' . implode(' ', $bits);
    }
}
