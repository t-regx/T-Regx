<?php
namespace TRegx\CleanRegex\Internal\Type;

class ValueType implements Type
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
            $type = \gettype($this->value);
            $value = \var_export($this->value, true);
            return "$type ($value)";
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
