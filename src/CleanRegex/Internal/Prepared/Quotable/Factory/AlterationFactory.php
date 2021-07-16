<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Prepared\Quotable\AlternationQuotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;
use TRegx\CleanRegex\Internal\ValueType;

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
        throw InvalidArgument::typeGiven("Invalid bound value. Expected string", new ValueType($value));
    }
}
