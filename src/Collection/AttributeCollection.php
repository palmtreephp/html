<?php

namespace Palmtree\Html\Collection;

class AttributeCollection extends AbstractCollection
{
    public function add(array $elements): self
    {
        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    public function setData(string $key, string $value = ''): self
    {
        $this->set("data-$key", $value);

        return $this;
    }

    public function removeData(string $key): self
    {
        return $this->remove("data-$key");
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

        $attributeStrings = [];
        foreach ($this->elements as $key => $value) {
            $attributeString = $key;
            if ($value !== null && $value !== '') {
                $attributeString .= '="' . $value . '"';
            }

            $attributeStrings[] = $attributeString;
        }

        return ' ' . implode(' ', $attributeStrings);
    }
}
