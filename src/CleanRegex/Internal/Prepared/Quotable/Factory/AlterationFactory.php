<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\Type;

class AlterationFactory implements QuotableFactory
{
    public function quotable($value): Quotable
    {
        if (\is_string($value)) {
            return new UserInputQuotable($value);
        }
        if (\is_array($value)) {
            return new AlternationQuotable($value);
        }
        $type = Type::asString($value);
        throw new InvalidArgumentException("Invalid bound value. Expected string, but $type given");
    }
}
