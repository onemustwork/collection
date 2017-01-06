<?php

namespace OneMustWork\Collection;

use Countable;
use IteratorAggregate;
use ArrayAccess;
use Traversable;
use ArrayIterator;
use JsonSerializable;

/**
 * Class Collection
 * @package OneMustWork\Collection
 */
class Collection implements Countable, IteratorAggregate, ArrayAccess, JsonSerializable
{
    /** @var array */
    private $items = [];

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($this->items as $key => $value) {
            $this->put($key, $value);
        }
    }

    /**
     * Adds new item to the collection
     *
     * @param mixed $value
     */
    public function add($value)
    {
        $this->items[] = $value;
    }

    /**
     * Checks if the given value exist in the collection
     *
     * @param mixed $value
     * @return bool
     */
    public function exists($value)
    {
        return in_array($value, $this->items, true);
    }

    /**
     * Puts mew item to the collection
     *
     * @param $key
     * @param $value
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof ArrayAccess ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * Convert the collection to an json string
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } else {
                return $value;
            }
        }, $this->items);
    }

    /**
     * Convert the collection to an string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}