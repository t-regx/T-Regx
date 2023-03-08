<?php
namespace TRegx\CleanRegex\Internal;

/**
 * @template K of array-key
 * @template V
 */
class NestedArray
{
    /** @var array<array<K, V>> */
    private $nestedValues;

    /**
     * @param array<array<K, V>> $array
     */
    public function __construct(array $array)
    {
        $this->nestedValues = $array;
    }

    /**
     * @return list<V>
     */
    public function valuesList(): array
    {
        if (empty($this->nestedValues)) {
            return [];
        }
        return \array_merge(...\array_map('array_values', $this->nestedValues));
    }

    /**
     * @return array<K, V>
     */
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
