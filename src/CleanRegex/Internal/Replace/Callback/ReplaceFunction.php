<?php
namespace TRegx\CleanRegex\Internal\Replace\Callback;

use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Detail;

class ReplaceFunction
{
    /** @var callable */
    private $function;

    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    public function apply(Detail $detail): string
    {
        $replacement = ($this->function)($detail);
        if (\is_string($replacement)) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }
}
