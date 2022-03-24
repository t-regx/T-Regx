<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Match\Details\Detail;

class CallableFunction implements DetailFunction
{
    /** @var callable */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function apply(Detail $detail)
    {
        return ($this->callable)($detail);
    }
}
