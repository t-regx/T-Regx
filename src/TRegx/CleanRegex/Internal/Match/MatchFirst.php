<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatch;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DetailImpl;

class MatchFirst
{
    /** @var Base */
    private $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
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
        return $this->createMatchObject($match);
    }

    private function validateMatched(IRawMatch $match): void
    {
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirst($this->base);
        }
    }

    private function createMatchObject(IRawMatchOffset $match): Detail
    {
        $factory = new LazyMatchAllFactory($this->base);
        return new DetailImpl($this->base, 0, 1, new GroupPolyfillDecorator($match, $factory, 0),
            $factory, $this->base->getUserData());
    }
}
