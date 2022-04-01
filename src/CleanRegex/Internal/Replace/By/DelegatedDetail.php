<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class DelegatedDetail
{
    /** @var Base */
    private $base;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $index;

    /** @var Detail|null */
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
            -99, // These values are never used, because `index()` and `limit()` in LazyMatch aren't
            -99, // passed through `Detail`, because they are read from fields.
            // We could pass real data here, but we could never test it, since the code doesn't
            // use those values. We could also pass it and read it, but then LazyDetail.index()
            // and  LazyDetail.limit() would perform match unnecessarily.
            new RawMatchesToMatchAdapter($matches, $this->index),
            new EagerMatchAllFactory($matches),
            new UserData());
    }
}
