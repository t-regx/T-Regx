<?php
namespace Danon\CleanRegex\Match;

use Danon\CleanRegex\Internal\Pattern;

class ReplaceMatch extends Match
{
    /** @var Pattern */
    private $pattern;
    /** @var int */
    private $offsetModification;

    public function __construct(string $subject, int $index, array $matches, Pattern $pattern, int $offsetModification)
    {
        parent::__construct($subject, $index, $matches);
        $this->pattern = $pattern;
        $this->offsetModification = $offsetModification;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }
}
