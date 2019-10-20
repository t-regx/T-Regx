<?php
namespace TRegx\CleanRegex\ForArray;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Type;
use TRegx\SafeRegex\preg;

class FilterArrayPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var array */
    private $array;
    /** @var bool */
    private $throwOnNonStringElements;

    public function __construct(InternalPattern $pattern, array $array, bool $strict = false)
    {
        $this->pattern = $pattern;
        $this->array = $array;
        $this->throwOnNonStringElements = $strict;
    }

    public function filter(): array
    {
        return \array_values($this->filterAssoc());
    }

    public function filterAssoc(): array
    {
        $onlyStrings = \array_filter($this->array, '\is_string');
        if ($this->throwOnNonStringElements) {
            $this->validateOnlyStrings($onlyStrings);
        }
        return preg::grep($this->pattern->pattern, $onlyStrings);
    }

    private function validateOnlyStrings(array $filteredArray): void
    {
        if (\count($filteredArray) != \count($this->array)) {
            $invalidValues = \array_diff_key($this->array, $filteredArray);
            $invalidTypeText = Type::asString(\reset($invalidValues));
            throw new InvalidArgumentException("Only elements of type `string` can be filtered, but $invalidTypeText given");
        }
    }
}
