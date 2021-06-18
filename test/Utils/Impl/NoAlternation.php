<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class NoAlternation extends AlterationFactory
{
    public function quotable($value): Quotable
    {
        throw new \AssertionError("Failed to assert that AlternationFactory wasn't used");
    }
}
