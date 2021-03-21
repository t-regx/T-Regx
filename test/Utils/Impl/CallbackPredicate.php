<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Match\Details\Detail;

class CallbackPredicate implements Predicate
{
    /** @var callable */
    private $predicate;

    public function __construct(callable $predicate)
    {
        $this->predicate = $predicate;
    }

    public function test(Detail $detail): bool
    {
        return ($this->predicate)($detail);
    }
}
