<?php

namespace Palmtree\Html\Collection;

abstract class AbstractCollection implements \ArrayAccess, \IteratorAggregate
{
    protected $elements = [];

    final public function __construct(array $elements = [])
    {
        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function get(string $key): ?string
    {
        return $this->elements[$key] ?? null;
    }

    public function set(string $key, ?string $value = ''): self
    {
        $this->elements[$key] = $value;

        return $this;
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function values(): self
    {
        return new static(array_values($this->elements));
    }

    public function has(string $key): bool
    {
        return isset($this->elements[$key]);
    }

    public function remove(string $key): self
    {
        unset($this->elements[$key]);

        return $this;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    abstract public function offsetSet($offset, $value): void;

    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }
}
