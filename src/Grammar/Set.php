<?php namespace Pharser\Grammar;

/**
 * A wrapper around an array with a few support functions.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
final class Set implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * The data stored inside this set.
     *
     * @var array
     */
    private $data;

    /**
     * @var null|string|Callable
     */
    private $validator = null;

    /**
     * Set constructor.
     *
     * @param array                $data
     * @param null|string|Callable $validator
     */
    public function __construct(array $data, $validator = null)
    {
        $this->data = $data;
        $this->validator = $validator;

        if ($this->validator) {
            $this->validateAll();
        }
    }

    /**
     * Validate all items in this set.
     *
     * @throws \InvalidArgumentException
     */
    private function validateAll()
    {
        array_walk($this->data, function ($item) {
            $this->validateItem($item);
        });
    }

    /**
     * Validate the given item against the validator of this set, throwing an exception if invalid.
     *
     * @param $item
     *
     * @throws \InvalidArgumentException
     */
    private function validateItem($item)
    {
        if (is_null($this->validator)) {
            return;
        }

        if (is_callable($this->validator) && !call_user_func($this->validator, $item)) {
            throw new \InvalidArgumentException(sprintf(
                'Item of type %s is not valid for set',
                is_scalar($item) ? gettype($item) : get_class($item)
            ));
        }

        if (
            is_string($this->validator) &&
            (class_exists($this->validator) || interface_exists($this->validator)) &&
            !($item instanceof $this->validator)
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Item of type %s is not valid for set',
                is_scalar($item) ? gettype($item) : get_class($item)
            ));
        }
    }

    /**
     * Set the validator for this set.
     *
     * @param null|string|Callable $validator
     *
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        $this->validateAll();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->validateItem($value);

        $this->data[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->offsetExists($this->key());
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Get the underlying array representation of this set.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Returns true if this set contains the given item.
     *
     * @param $item
     *
     * @return bool
     */
    public function contains($item)
    {
        return array_search($item, $this->data, true) !== false;
    }

    /**
     * Returns true if this set contains any item which, when passed to the callable, returns true.
     *
     * @param callable $callable
     *
     * @return bool
     */
    public function hasMatching(callable $callable)
    {
        foreach ($this->data as $item) {
            if (call_user_func($callable, $item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return all of the items from this set which pass the given callback.
     *
     * @param callable $callable
     *
     * @return Set
     */
    public function filter(callable $callable)
    {
        return new Set(
            array_values(
                array_filter($this->data, $callable)
            ),
            $this->validator
        );
    }

    /**
     * Return all of the items from this set which do not pass the given callback.
     *
     * @param callable $callable
     *
     * @return Set
     */
    public function exclude(callable $callable)
    {
        return $this->filter(function () use ($callable) {
            return !call_user_func_array($callable, func_get_args());
        });
    }
}
