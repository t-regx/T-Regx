<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchesFirstPrime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Detail;

class DelegatedDetail
{
    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $index;

    /** @var \TRegx\CleanRegex\Match\Detail|null */
    private $detail = null;

    public function __construct(Base $base, Subject $subject, int $index)
    {
        $this->base = $base;
        $this->subject = $subject;
        $this->index = $index;
    }

    public function detail(): Detail
    {
        $this->detail = $this->detail ?? $this->matchedDetail();
        return $this->detail;
    }

    private function matchedDetail(): Detail
    {
        $matches = $this->base->matchAllOffsets();
        return DeprecatedMatchDetail::create(
            $this->subject,
            $this->index,
            new RawMatchesToMatchAdapter($matches, $this->index),
            new EagerMatchAllFactory($matches),
            new MatchesFirstPrime($matches));
    }
}
