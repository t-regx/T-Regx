<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\Details\Group\ReplaceMatchGroup;
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
        parent::__construct($subject, $index, $matches, new ReplaceMatchGroupFactoryStrategy($offsetModification));
        $this->offsetModification = $offsetModification;
        $this->subjectModification = $subjectModification;
        $this->limit = $limit;
    }

    public function modifiedOffset(): int
    {
        return $this->offset() + $this->offsetModification;
    }

    public function modifiedSubject(): string
    {
        return $this->subjectModification;
    }

    public function all(): array
    {
        if ($this->limit === -1) {
            return parent::all();
        }
        return array_slice(parent::all(), 0, $this->limit);
    }

    public function allUnlimited(): array
    {
        return $this->getFirstFromAllMatches();
    }

    /**
     * @param string|int $nameOrIndex
     * @return ReplaceMatchGroup
     * @throws NonexistentGroupException
     */
    public function group($nameOrIndex): MatchGroup
    {
        /** @var ReplaceMatchGroup $matchGroup */
        $matchGroup = parent::group($nameOrIndex);
        return $matchGroup;
    }
}
