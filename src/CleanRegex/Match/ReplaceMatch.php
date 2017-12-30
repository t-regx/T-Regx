<?php
namespace CleanRegex\Match;

class ReplaceMatch extends Match
{
    /** @var int */
    private $offsetModification;

    public function __construct(string $subject, int $index, array $matches, int $offsetModification)
    {
        parent::__construct($subject, $index, $matches);
        $this->offsetModification = $offsetModification;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }
}
