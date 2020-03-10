<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\Quoteable;
use TRegx\CleanRegex\Internal\Prepared\Quoteable\UserInputQuoteable;
use TRegx\CleanRegex\Internal\Type;

class QuotableFactory
{
    public static function quotable($value): Quoteable
    {
        if (\is_string($value)) {
            return new UserInputQuoteable($value);
        }
        if (\is_array($value)) {
            return new AlternationQuotable($value);
        }
        $type = Type::asString($value);
        throw new InvalidArgumentException("Invalid bound value. Expected string, but $type given");
    }
}
