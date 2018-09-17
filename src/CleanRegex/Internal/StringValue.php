<?php
namespace CleanRegex\Internal;

class StringValue
{
    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getString(): string
    {
        if ($this->value === null) {
            return 'null';
        }
        if (is_scalar($this->value)) {
            return $this->scalar();
        }
        if (is_array($this->value)) {
            $count = count($this->value);
            return "array ($count)";
        }
        if (is_resource($this->value)) {
            return 'resource';
        }
        return get_class($this->value);
    }

    private function scalar(): string
    {
        $type = gettype($this->value);
        $value = var_export($this->value, true);
        return "$type ($value)";
    }
}
