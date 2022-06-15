<?php
namespace TRegx\CleanRegex\Internal;

class NestedArray
{
    /** @var array */
    private $nestedValues;

    public function __construct(array $array)
    {
        $this->nestedValues = $array;
    }

    public function valuesList(): array
    {
        if (empty($this->nestedValues)) {
            return [];
        }
        return \array_merge(...\array_map('array_values', $this->nestedValues));
    }

    public function valuesDictionary(): array
    {
        $result = [];
        foreach ($this->nestedValues as $values) {
            foreach ($values as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
