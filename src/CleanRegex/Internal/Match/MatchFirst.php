<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;

class MatchFirst
{
    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var UserData */
    private $userData;

    public function __construct(Base $base, Subject $subject, UserData $userData, MatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->subject = $subject;
        $this->allFactory = $allFactory;
        $this->userData = $userData;
    }

    public function matchDetails(): Detail
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $this->detail($match->getIndex(), new FalseNegative($match));
        }
        throw SubjectNotMatchedException::forFirst($this->subject);
    }

    private function detail(int $index, FalseNegative $false): Detail
    {
        return MatchDetail::create($this->subject,
            $index,
            1,
            new GroupPolyfillDecorator($false, $this->allFactory, $index),
            $this->allFactory,
            $this->userData);
    }
}
