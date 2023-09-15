<?php
namespace Regex\Internal;

class Type
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        if ($this->value === null) {
            return 'null';
        }
        if (\is_scalar($this->value)) {
            return \getType($this->value) . " ($this->value)";
        }
        if (\is_array($this->value)) {
            $count = \count($this->value);
            return "array ($count)";
        }
        if (\is_resource($this->value)) {
            return 'resource';
        }
        return \get_class($this->value);
    }
}
