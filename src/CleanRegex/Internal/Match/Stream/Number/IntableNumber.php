<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Number;

use TRegx\CleanRegex\Internal\Numeral\Base;
use TRegx\CleanRegex\Match\Details\Intable;

class IntableNumber implements Number
{
    /** @var Intable */
    private $intable;
    /** @var Base */
    private $base;

    public function __construct(Intable $intable, Base $base)
    {
        $this->intable = $intable;
        $this->base = $base;
    }

    public function toInt(): int
    {
        return $this->intable->toInt($this->base->base());
    }
}
