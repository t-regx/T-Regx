<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;

class GroupByFunction
{
    /** @var string */
    private $methodName;
    /** @var callable */
    private $function;

    public function __construct(string $methodName, callable $function)
    {
        $this->methodName = $methodName;
        $this->function = $function;
    }

    /**
     * @param $argument
     * @return int|string
     */
    public function apply($argument)
    {
        $newKey = ($this->function)($argument);
        if ($newKey instanceof Detail || $newKey instanceof Group) {
            return $newKey->text();
        }
        if (\is_int($newKey) || \is_string($newKey)) {
            return $newKey;
        }
        throw new InvalidReturnValueException($this->methodName, 'int|string', new ValueType($newKey));
    }
}
