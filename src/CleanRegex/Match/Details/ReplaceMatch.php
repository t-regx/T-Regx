<?php
namespace CleanRegex\Match\Details;

use function array_slice;

class ReplaceMatch extends Match
{
    /** @var int */
    private $offsetModification;
    /** @var string */
    private $subjectModification;
    /** @var int */
    private $limit;

    public function __construct(string $subject, int $index, array $matches, int $offsetModification, string $subjectModification, int $limit)
    {
        parent::__construct($subject, $index, $matches);
        $this->offsetModification = $offsetModification;
        $this->subjectModification = $subjectModification;
        $this->limit = $limit;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }

    public function allUnlimited(): array
    {
        return $this->getFirstFromAllMatches();
    }

    public function all(): array
    {
        if ($this->limit === -1) {
            return parent::all();
        }
        return array_slice(parent::all(), 0, $this->limit);
    }

    public function modifiedSubject(): string
    {
        return $this->subjectModification;
    }
}
