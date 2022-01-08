<?php

declare(strict_types=1);

namespace Palmtree\Html\Collection;

/**
 * @template TKey of array-key
 * @template-implements \ArrayAccess<TKey, string>
 * @template-implements \IteratorAggregate<TKey, string>
 */
abstract class AbstractCollection implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @psalm-var array<TKey, string>  */
    protected $elements = [];

    /**
     * @psalm-param array<TKey, string> $elements
     */
    final public function __construct(array $elements = [])
    {
        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @psalm-param TKey $key
     */
    public function get($key): ?string
    {
        return $this->elements[$key] ?? null;
    }

    /**
     * @psalm-param TKey $key
     */
    public function set($key, string $value = ''): self
    {
        $this->elements[$key] = $value;

        return $this;
    }

    /**
     * @psalm-return array<TKey, string>
     */
    public function all(): array
    {
        return $this->elements;
    }

    public function clear(): void
    {
        $this->elements = [];
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

    public function count(): int
    {
        return \count($this->elements);
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * @psalm-return \ArrayIterator<TKey, string>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @psalm-param TKey $offset
     */
    public function offsetGet($offset): ?string
    {
        return $this->get($offset);
    }

    abstract public function offsetSet($offset, $value): void;

    /**
     * @psalm-param TKey $offset
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }
}
