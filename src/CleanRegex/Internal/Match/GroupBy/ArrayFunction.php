<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;

class ArrayFunction implements DetailFunction
{
    /** @var callable */
    private $mapper;
    /** @var string */
    private $methodName;

    public function __construct(callable $mapper, string $methodName)
    {
        $this->mapper = $mapper;
        $this->methodName = $methodName;
    }

    public function apply(Detail $detail): array
    {
        $result = ($this->mapper)($detail);
        if (\is_array($result)) {
            return $result;
        }
        throw new InvalidReturnValueException($this->methodName, 'array', new ValueType($result));
    }
}
