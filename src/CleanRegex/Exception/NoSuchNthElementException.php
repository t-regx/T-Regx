<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class NoSuchNthElementException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forSubject(int $index, int $total): self
    {
        return new self("Expected to get the $index-nth match, but only $total occurrences were matched");
    }

    public static function forGroup(GroupKey $group, int $index, int $total): self
    {
        return new self("Expected to get group $group from the $index-nth match, but only $total occurrences were matched");
    }
}
