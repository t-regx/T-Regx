<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Number;

class IdentityNumber implements Number
{
    /** @var int */
    private $integer;

    public function __construct(int $integer)
    {
        $this->integer = $integer;
    }

    public function toInt(): int
    {
        return $this->integer;
    }
}
