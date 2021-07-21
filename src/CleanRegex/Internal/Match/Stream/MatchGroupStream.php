<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\GroupVerifier;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Group\Group;

class MatchGroupStream implements Stream
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Base $base, $nameOrIndex, MatchAllFactory $factory)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->allFactory = $factory;
        $this->groupVerifier = new GroupVerifier($this->base->getPattern());
    }

    /**
     * @return Group[]
     */
    public function all(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$matches->matched()) {
            throw new UnmatchedStreamException();
        }
        return (new GroupFacade($matches, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new EagerMatchAllFactory($matches)))->createGroups($matches);
    }

    public function first(): Group
    {
        $match = $this->base->matchOffset();
        $this->validateGroupOrSubject($match);
        return (new GroupFacade($match, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), $this->allFactory))->createGroup($match);
    }

    public function firstKey(): int
    {
        $match = $this->base->matchOffset();
        $this->validateGroupOrSubject($match);
        return $match->getIndex();
    }

    private function validateGroupOrSubject(RawMatchOffset $match): void
    {
        if ($match->hasGroup($this->nameOrIndex)) {
            return;
        }
        if (!$this->groupVerifier->groupExists($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$match->matched()) {
            throw new UnmatchedStreamException();
        }
    }
}
