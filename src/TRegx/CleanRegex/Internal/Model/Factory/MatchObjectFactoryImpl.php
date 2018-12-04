<?php
namespace TRegx\CleanRegex\Internal\Model\Factory;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\MatchImpl;

class MatchObjectFactoryImpl implements MatchObjectFactory
{
    /** @var Subjectable */
    private $subjectable;
    /** @var UserData */
    private $userData;

    public function __construct(Subjectable $subjectable, UserData $userData)
    {
        $this->subjectable = $subjectable;
        $this->userData = $userData;
    }

    public function create(int $index, IRawMatchOffset $matchOffset, MatchAllFactory $matchAllFactory)
    {
        return new MatchImpl($this->subjectable, $index, $matchOffset, $matchAllFactory, $this->userData);
    }
}
