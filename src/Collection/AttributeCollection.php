<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template-extends \ArrayObject<string, string>
 */
class AttributeCollection extends \ArrayObject
{
    public function set(string $key, ?string $value): self
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
        return array_values($this->getArrayCopy());
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
