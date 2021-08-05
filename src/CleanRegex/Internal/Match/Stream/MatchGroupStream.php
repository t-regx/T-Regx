<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\GroupKey\ArraySignatures;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\PerformanceSignatures;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\Handle\FirstNamedGroup;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\NotMatched;

class MatchGroupStream implements Stream
{
    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $groupId;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupAware $groupAware, GroupKey $groupId, MatchAllFactory $factory)
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
        $signatures = new ArraySignatures($matches->getGroupKeys());
        $facade = new GroupFacade($this->base, $this->groupId, new MatchGroupFactoryStrategy(),
            new EagerMatchAllFactory($matches),
            new NotMatched($matches, $this->base),
            new FirstNamedGroup($signatures),
            $signatures);
        return $facade->createGroups($matches);
    }

    public function first(): Group
    {
        $match = $this->base->matchOffset();
        $this->validateGroupOrSubject($match);
        $false = new FalseNegative($match);
        $polyfill = new GroupPolyfillDecorator($false, $this->allFactory, 0);
        $signatures = new PerformanceSignatures($match, $this->groupAware);
        $groupFacade = new GroupFacade($this->base, $this->groupId,
            new MatchGroupFactoryStrategy(),
            $this->allFactory,
            new NotMatched($this->groupAware, $this->base),
            new FirstNamedGroup($signatures), $signatures);
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
