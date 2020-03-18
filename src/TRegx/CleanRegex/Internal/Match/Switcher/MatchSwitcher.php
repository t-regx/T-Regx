<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;
use TRegx\CleanRegex\Match\Details\Match;

class MatchSwitcher implements Switcher
{
    /** @var BaseSwitcher */
    private $switcher;
    /** @var MatchObjectFactory */
    private $factory;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(BaseSwitcher $switcher, MatchObjectFactory $factory, MatchAllFactory $allFactory)
    {
        $this->switcher = $switcher;
        $this->factory = $factory;
        $this->allFactory = $allFactory;
    }

    public function all(): array
    {
        return $this->switcher->all()->getMatchObjects($this->factory);
    }

    public function first(): Match
    {
        return $this->factory->create(0, $this->switcher->first(), $this->allFactory);
    }
}
