<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;

class MatchStream implements Stream
{
    use PreservesKey;

    /** @var BaseStream */
    private $stream;
    /** @var Subjectable */
    private $subjectable;
    /** @var UserData */
    private $userData;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(BaseStream $stream, Subjectable $subjectable, UserData $userData, MatchAllFactory $allFactory)
    {
        $this->stream = $stream;
        $this->subjectable = $subjectable;
        $this->userData = $userData;
        $this->allFactory = $allFactory;
    }

    public function all(): array
    {
        return $this->stream->all()->getDetailObjects(new DetailObjectFactory($this->subjectable, -1, $this->userData));
    }

    public function first(): Detail
    {
        return new MatchDetail($this->subjectable,
            $this->stream->firstKey(),
            1,
            $this->stream->first(),
            $this->allFactory,
            $this->userData);
    }
}
