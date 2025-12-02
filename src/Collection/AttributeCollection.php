<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template-extends \ArrayObject<string, string>
 */
class AttributeCollection extends \ArrayObject implements \Stringable
{
    /**
     * @param array<string, string|null> $elements
     */
    public function add(array $elements): self
    {
        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    public function set(string $key, ?string $value = null): self
    {
        $this[$key] = $value ?? '';

        return $this;
    }

    public function setData(string $key, string $value = ''): self
    {
        $this["data-$key"] = $value;

        return $this;
    }

    public function removeData(string $key): self
    {
        unset($this["data-$key"]);

        return $this;
    }

    /**
     * @return list<string>
     */
    public function values(): array
    {
        return array_values((array)$this);
    }

    public function clear(): void
    {
        $this->exchangeArray([]);
    }

    public function __toString(): string
    {
        if ($this->count() === 0) {
            return '';
        }

        $attributeStrings = [];
        foreach ($this as $key => $value) {
            $attributeString = $key;
            if ($value !== '') {
                $attributeString .= '="' . $value . '"';
            }

            $attributeStrings[] = $attributeString;
        }

        return ' ' . implode(' ', $attributeStrings);
    }
}
