<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;

class DetailObjectFactory
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

    public function create(int $index, IRawMatchOffset $matchOffset, MatchAllFactory $matchAllFactory): Detail
    {
        return MatchDetail::create($this->subjectable, $index, -1, $matchOffset, $matchAllFactory, $this->userData);
    }
}
