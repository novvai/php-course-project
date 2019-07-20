<?php

namespace Novvai\Stacks;

use ArrayIterator;
use IteratorAggregate;
use Novvai\Interfaces\Arrayable;
use Novvai\Stacks\Interfaces\Stackable;

abstract class Base implements IteratorAggregate, Stackable, Arrayable
{
    protected $items = [];
    
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Adds item to the internal storage
     * 
     * @param mixed $data
     * @return Stackable
     */
    public function add($data): Stackable
    {
        $item = is_array($data) ? $data : [$data];
        $this->items = array_merge($this->items, $item);
        return $this;
    }

    /**
     * Creates fills internal storage
     * 
     * @param array $data
     * @return Stackable
     */
    public function collect(array $data): Stackable
    {
        $this->items = $data;

        return $this;
    }

    /**
     * Extracts the first available item
     * Return null if there are no items
     * 
     * @return mixed|null
     */
    public function first()
    {
        $item = reset($this->items);

        return $item ?: null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->items);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return map($this->items, function ($item) {
            if ($item instanceof Arrayable) {
                return $item->toArray();
            }
            return $item;
        });
    }
}
