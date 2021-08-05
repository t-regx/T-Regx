<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
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

    public function matchDetails(): Detail
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $this->detail($match->getIndex(), new FalseNegative($match));
        }
        throw SubjectNotMatchedException::forFirst($this->base);
    }

    private function detail(int $index, FalseNegative $false): Detail
    {
        return MatchDetail::create($this->base,
            $index,
            1,
            new GroupPolyfillDecorator($false, $this->allFactory, $index),
            $this->allFactory,
            $this->base->getUserData());
    }
}
