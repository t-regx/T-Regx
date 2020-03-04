<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\MatchImpl;

class MatchObjectFactory
{
    /** @var Subjectable */
    private $subjectable;
    /** @var UserData */
    private $userData;
    /** @var int|null */
    private $limit;

    public function __construct(Subjectable $subjectable, ?int $limit, UserData $userData)
    {
        $this->subjectable = $subjectable;
        $this->userData = $userData;
        $this->limit = $limit;
    }

    public function create(int $index, IRawMatchOffset $matchOffset, MatchAllFactory $matchAllFactory)
    {
        return new MatchImpl($this->subjectable, $index, $this->limit, $matchOffset, $matchAllFactory, $this->userData);
    }
}
