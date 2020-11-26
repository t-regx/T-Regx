<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DetailImpl;

class DetailObjectFactory
{
    /** @var Subjectable */
    private $subjectable;
    /** @var int|null */
    private $limit;
    /** @var UserData */
    private $userData;

    public function __construct(Subjectable $subjectable, ?int $limit, UserData $userData)
    {
        $this->subjectable = $subjectable;
        $this->limit = $limit;
        $this->userData = $userData;
    }

    public function create(int $index, IRawMatchOffset $matchOffset, MatchAllFactory $matchAllFactory): Detail
    {
        return new DetailImpl($this->subjectable, $index, $this->limit, $matchOffset, $matchAllFactory, $this->userData);
    }
}
