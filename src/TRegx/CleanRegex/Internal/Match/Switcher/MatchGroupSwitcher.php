<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchGroupSwitcher implements Switcher
{
    /** @var BaseSwitcher */
    private $switcher;
    /** @var Subjectable */
    private $subjectable;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(BaseSwitcher $switcher, Subjectable $subjectable, $nameOrIndex, MatchAllFactory $factory)
    {
        $this->switcher = $switcher;
        $this->subjectable = $subjectable;
        $this->nameOrIndex = $nameOrIndex;
        $this->allFactory = $factory;
    }

    /**
     * @return MatchGroup[]
     */
    public function all(): array
    {
        $matches = $this->switcher->all();
        return $this->facade($matches, new EagerMatchAllFactory($matches))->createGroups($matches);
    }

    public function first(): MatchGroup
    {
        $match = $this->switcher->first();
        return $this->facade($match, $this->allFactory)->createGroup($match);
    }

    private function facade(IRawWithGroups $matches, MatchAllFactory $allFactory): GroupFacade
    {
        return new GroupFacade($matches, $this->subjectable, $this->nameOrIndex, new MatchGroupFactoryStrategy(), $allFactory);
    }

    public function firstKey(): int
    {
        return 0;
    }
}
