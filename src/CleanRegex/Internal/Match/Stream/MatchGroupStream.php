<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
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
    /** @var GroupKey */
    private $groupId;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupHasAware $groupAware, GroupKey $groupId, MatchAllFactory $factory)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->groupId = $groupId;
        $this->allFactory = $factory;
    }

    /**
     * @return Group[]
     */
    public function all(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if (!$matches->matched()) {
            throw new UnmatchedStreamException();
        }
        return (new GroupFacade($matches, $this->base, $this->groupId, new MatchGroupFactoryStrategy(), new EagerMatchAllFactory($matches)))->createGroups($matches);
    }

    public function first(): Group
    {
        $match = $this->base->matchOffset();
        $this->validateGroupOrSubject($match);
        $groupFacade = new GroupFacade($match, $this->base, $this->groupId, new MatchGroupFactoryStrategy(), $this->allFactory);
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0);
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
        if ($match->hasGroup($this->groupId->nameOrIndex())) {
            return;
        }
        if (!$this->groupAware->hasGroup($this->groupId->nameOrIndex())) {
            throw new NonexistentGroupException($this->groupId);
        }
        if (!$match->matched()) {
            throw new UnmatchedStreamException();
        }
    }
}
