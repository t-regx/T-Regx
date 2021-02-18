<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatch;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;

class MatchFirst
{
    /** @var Base */
    private $base;
    /** @var LazyMatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, LazyMatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->allFactory = $allFactory;
    }

    public function invoke(?callable $consumer)
    {
        if ($consumer === null) {
            return $this->getFirstText();
        }
        return $consumer($this->matchDetails());
    }

    private function getFirstText(): string
    {
        $match = $this->base->match();
        $this->validateMatched($match);
        return $match->getText();
    }

    private function matchDetails(): Detail
    {
        $match = $this->base->matchOffset();
        $this->validateMatched($match);
        return $this->createDetail($match);
    }

    private function validateMatched(IRawMatch $match): void
    {
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirst($this->base);
        }
    }

    private function createDetail(RawMatchOffset $match): Detail
    {
        return new MatchDetail($this->base,
            $match->getIndex(),
            1,
            new GroupPolyfillDecorator($match, $this->allFactory, $match->getIndex()),
            $this->allFactory,
            $this->base->getUserData()
        );
    }
}
