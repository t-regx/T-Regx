<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;

class MappingAlternation extends AlterationFactory
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        parent::__construct('');
        $this->mapper = $mapper;
    }

    public function quotable($value): Quotable
    {
        $value1 = ($this->mapper)($value);
        return new RawQuotable($value1);
    }
}
