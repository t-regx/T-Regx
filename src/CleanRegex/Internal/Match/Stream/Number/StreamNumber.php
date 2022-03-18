<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Number;

use TRegx\CleanRegex\Internal\Match\Numeral\MatchBase;
use TRegx\CleanRegex\Internal\Match\Numeral\StreamExceptions;
use TRegx\CleanRegex\Internal\Numeral\Base;

class StreamNumber implements Number
{
    /** @var Base */
    private $base;
    /** @var string */
    private $string;

    public function __construct(string $string, Base $base)
    {
        $this->base = new MatchBase($base, new StreamExceptions());
        $this->string = $string;
    }

    public function toInt(): int
    {
        return $this->base->integer($this->string);
    }
}
