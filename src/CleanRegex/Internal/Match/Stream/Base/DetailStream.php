<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchPrime;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Detail;

class DetailStream implements Upstream
{
    use ListStream;

    /** @var StreamBase */
    private $stream;
    /** @var Subject */
    private $subject;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var DetailObjectFactory */
    private $detailObjects;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(StreamBase $stream, Subject $subject, MatchAllFactory $allFactory, GroupAware $groupAware)
    {
        $this->stream = $stream;
        $this->subject = $subject;
        $this->allFactory = $allFactory;
        $this->detailObjects = new DetailObjectFactory($subject);
        $this->groupAware = $groupAware;
    }

    protected function entries(): array
    {
        return $this->detailObjects->mapToDetailObjects($this->stream->all());
    }

    protected function firstValue(): Detail
    {
        $match = $this->stream->first();
        return DeprecatedMatchDetail::create($this->subject,
            0,
            new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0, $this->groupAware),
            $this->allFactory,
            new MatchPrime($match));
    }
}
