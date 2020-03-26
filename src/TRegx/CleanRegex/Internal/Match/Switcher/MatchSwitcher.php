<?php
namespace TRegx\CleanRegex\Internal\Match\Switcher;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Match;

class MatchSwitcher implements Switcher
{
    /** @var BaseSwitcher */
    private $switcher;
    /** @var Subjectable */
    private $subjectable;
    /** @var UserData */
    private $userData;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(BaseSwitcher $switcher, Subjectable $subjectable, UserData $userData, MatchAllFactory $allFactory)
    {
        $this->switcher = $switcher;
        $this->subjectable = $subjectable;
        $this->userData = $userData;
        $this->allFactory = $allFactory;
    }

    public function all(): array
    {
        return $this->switcher->all()->getMatchObjects($this->factory(-1));
    }

    public function first(): Match
    {
        return $this->factory(1)->create(0, $this->switcher->first(), $this->allFactory);
    }

    private function factory(int $limit): MatchObjectFactory
    {
        return new MatchObjectFactory($this->subjectable, $limit, $this->userData);
    }

    public function firstKey(): int
    {
        return $this->switcher->firstKey();
    }
}
