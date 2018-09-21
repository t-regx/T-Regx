<?php
namespace CleanRegex\Internal;

use CleanRegex\Exception\CleanRegex\NonexistentGroupException;
use CleanRegex\Match\GroupLimit;
use CleanRegex\Match\Groups\Strategy\GroupVerifier;
use SafeRegex\preg;
use function array_key_exists;

class GroupLimitFactory
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var GroupVerifier */
    private $groupVerifier;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Pattern $pattern, string $subject, GroupVerifier $groupVerifier, $nameOrIndex)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->groupVerifier = $groupVerifier;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function create(): GroupLimit
    {
        return new GroupLimit(
            function () {
                return $this->getAllForGroup();
            },
            function () {
                return $this->getFirstForGroup();
            });
    }

    private function getAllForGroup(): array
    {
        $matches = [];
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        if (array_key_exists($this->nameOrIndex, $matches)) {
            return $matches[$this->nameOrIndex];
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    private function getFirstForGroup(): ?string
    {
        $matches = [];
        preg::match($this->pattern->pattern, $this->subject, $matches, $this->pregMatchFlags());
        if (array_key_exists($this->nameOrIndex, $matches)) {
            return $matches[$this->nameOrIndex];
        }
        if ($this->groupExists()) {
            return null;
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    private function pregMatchFlags(): int
    {
        if (defined('PREG_UNMATCHED_AS_NULL')) {
            return PREG_UNMATCHED_AS_NULL;
        }
        return 0;
    }

    private function groupExists(): bool
    {
        return $this->groupVerifier->groupExists($this->pattern->pattern, $this->nameOrIndex);
    }
}
