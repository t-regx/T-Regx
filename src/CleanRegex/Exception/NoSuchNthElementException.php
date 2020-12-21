<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class NoSuchNthElementException extends PatternException
{
    /** @var int */
    private $index;
    /** @var int */
    private $total;

    public function __construct(string $message, int $index, int $total)
    {
        parent::__construct($message);
        $this->index = $index;
        $this->total = $total;
    }

    public static function forSubject(int $index, int $total): self
    {
        return new self("Expected to get the $index-nth match, but only $total occurrences were matched", $index, $total);
    }

    public static function forGroup($nameOrIndex, int $index, int $total): self
    {
        $group = Type::group($nameOrIndex);
        return new self("Expected to get group $group from the $index-nth match, but only $total occurrences were matched", $index, $total);
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
