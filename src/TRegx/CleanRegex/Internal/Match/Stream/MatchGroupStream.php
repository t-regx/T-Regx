<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchGroupStream implements Stream
{
    /** @var BaseStream */
    private $stream;
    /** @var Subjectable */
    private $subjectable;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(BaseStream $stream, Subjectable $subjectable, $nameOrIndex, MatchAllFactory $factory)
    {
        $this->stream = $stream;
        $this->subjectable = $subjectable;
        $this->nameOrIndex = $nameOrIndex;
        $this->allFactory = $factory;
    }

    /**
     * @return MatchGroup[]
     */
    public function all(): array
    {
        $matches = $this->stream->all();
        return $this->facade($matches, new EagerMatchAllFactory($matches))->createGroups($matches);
    }

    public function first(): MatchGroup
    {
        $match = $this->stream->first();
        return $this->facade($match, $this->allFactory)->createGroup($match);
    }

    private function facade(IRawWithGroups $matches, MatchAllFactory $allFactory): GroupFacade
    {
        if ($matches->hasGroup($this->nameOrIndex)) {
            return new GroupFacade($matches, $this->subjectable, $this->nameOrIndex, new MatchGroupFactoryStrategy(), $allFactory);
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    public function firstKey(): int
    {
        return 0;
    }
}
