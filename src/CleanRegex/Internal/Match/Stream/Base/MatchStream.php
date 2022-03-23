<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Details\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;
    /** @var Subject */
    private $subject;
    /** @var UserData */
    private $userData;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var DetailObjectFactory */
    private $detailObjects;

    public function __construct(StreamBase $stream, Subject $subject, UserData $userData, MatchAllFactory $allFactory)
    {
        $this->stream = $stream;
        $this->subject = $subject;
        $this->userData = $userData;
        $this->allFactory = $allFactory;
        $this->detailObjects = new DetailObjectFactory($subject, $userData);
    }

    protected function entries(): array
    {
        return $this->detailObjects->mapToDetailObjects($this->stream->all());
    }

    protected function firstValue(): Detail
    {
        return DeprecatedMatchDetail::create($this->subject,
            $this->tryFirstKey(),
            1,
            new GroupPolyfillDecorator(new FalseNegative($this->stream->first()), $this->allFactory, 0),
            $this->allFactory,
            $this->userData);
    }

    private function tryFirstKey(): int
    {
        try {
            return $this->stream->firstKey();
        } catch (UnmatchedStreamException $exception) {
            throw new StreamRejectedException($this->subject, SubjectNotMatchedException::class, new FirstMatchMessage());
        }
    }
}
