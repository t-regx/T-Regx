<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Group\Group;

class MatchGroupStream implements Stream
{
    /** @var Base */
    private $base;
    /** @var GroupHasAware */
    private $groupAware;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupHasAware $groupAware, $nameOrIndex, MatchAllFactory $factory)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->nameOrIndex = $nameOrIndex;
        $this->allFactory = $factory;
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
        $groupFacade = new GroupFacade($match, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), $this->allFactory);
        $polyfill = new GroupPolyfillDecorator($match, $this->allFactory, 0);
        return $groupFacade->createGroup($polyfill);
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
        if (!$this->groupAware->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$match->matched()) {
            throw new UnmatchedStreamException();
        }
    }
}
