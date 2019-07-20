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

    public function add(array $data)
    {
        $this->items = array_merge($this->items, $data);
        return $this;
    }

    public function collect(array $data)
    {
        $this->items = $data;

        return $this;
    }

    public function first()
    {
        $item = reset($this->items);

        return $item?:null;
    }

    public function __toString()
    {
        return json_encode($this->items);
    }
    public function toArray()
    {
        return map($this->items, function ($item)
        {
            if ($item instanceof Arrayable) {
                return $item->toArray();
            }
            return $item;
        });
    }
}
