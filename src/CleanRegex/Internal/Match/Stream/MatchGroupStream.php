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
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Match\Details\NotMatched;

class MatchGroupStream implements Stream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupAware $groupAware, GroupKey $group, MatchAllFactory $factory)
    {
        $this->base = $base;
        $this->groupAware = $groupAware;
        $this->group = $group;
        $this->allFactory = $factory;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$matches->matched()) {
            throw new UnmatchedStreamException();
        }
        $signatures = new ArraySignatures($matches->getGroupKeys());
        $facade = new GroupFacade($this->base,
            new MatchGroupFactoryStrategy(),
            new EagerMatchAllFactory($matches),
            new NotMatched($matches, $this->base),
            new FirstNamedGroup($signatures),
            $signatures);
        return $facade->createGroups($this->group, $matches);
    }

    protected function firstValue(): Group
    {
        $match = $this->base->matchOffset();
        if (!$match->hasGroup($this->group->nameOrIndex())) {
            if (!$this->groupAware->hasGroup($this->group->nameOrIndex())) {
                throw new NonexistentGroupException($this->group);
            }
        }
        if (!$match->matched()) {
            throw new UnmatchedStreamException();
        }
        $signatures = new PerformanceSignatures($match, $this->groupAware);
        $groupFacade = new GroupFacade($this->base,
            new MatchGroupFactoryStrategy(),
            $this->allFactory,
            new NotMatched($this->groupAware, $this->base),
            new FirstNamedGroup($signatures), $signatures);
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0);
        return $groupFacade->createGroup($this->group, $polyfill, $polyfill);
    }
}
