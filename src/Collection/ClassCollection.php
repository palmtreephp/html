<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template-extends \ArrayObject<int|null, string>
 */
class ClassCollection extends \ArrayObject
{
    /**
     * @param string ...$elements
     */
    public function add(...$elements): self
    {
        foreach ($elements as $value) {
            $this[] = $value;
        }

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

    public function contains(string $value): bool
    {
        return \in_array($value, (array)$this, true);
    }

    public function remove(string $value): bool
    {
        $key = array_search($value, (array)$this, true);

        if ($key !== false) {
            unset($this[$key]);

            return true;
        }

        return false;
    }

    public function __toString(): string
    {
        if ($this->count() === 0) {
            return '';
        }

        return ' class="' . implode(' ', $this->values()) . '"';
    }
}
