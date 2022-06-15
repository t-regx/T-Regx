<?php
namespace TRegx\CleanRegex\Exception;

class NoSuchNthElementException extends \Exception implements PatternException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function forSubject(int $index, int $total): self
    {
        $message = "Expected to get the $index-nth match";
        if ($total === 0) {
            return new self("$message, but subject was not matched at all");
        }
        return new self("$message, but only $total occurrences were matched");
    }
}
