<?php
namespace TRegx\CleanRegex\Internal\Match\Numeral;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Numeral\Base;

class GroupExceptions implements IntegerExceptions
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function formatException(Base $base, string $numeral): IntegerFormatException
    {
        return new IntegerFormatException("Expected to parse group $this->group, but '$numeral' is not a valid integer in base $base");
    }

    public function overflowException(Base $base, string $numeral): IntegerOverflowException
    {
        return new IntegerOverflowException("Expected to parse group $this->group, but '$numeral' exceeds integer size on this architecture in base $base");
    }
}
