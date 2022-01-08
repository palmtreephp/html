<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template-extends AbstractCollection<string>
 */
final class AttributeCollection extends AbstractCollection
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
        $this->remove("data-$key");

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

        $attributeStrings = [];
        foreach ($this->elements as $key => $value) {
            $attributeString = $key;
            if ($value !== '') {
                $attributeString .= '="' . $value . '"';
            }

            $attributeStrings[] = $attributeString;
        }

        return ' ' . implode(' ', $attributeStrings);
    }
}
