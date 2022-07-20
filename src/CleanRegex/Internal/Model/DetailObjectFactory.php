<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\Details\MatchDetail;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchesFirstPrime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @deprecated
 */
class DetailObjectFactory
{
    /** @var Subject */
    private $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function mapToDetailObjects(RawMatchesOffset $matches): array
    {
        $matchObjects = [];
        foreach ($matches->matches[0] as $index => $firstWhole) {
            $matchObjects[] = $this->mapToDetailObject($matches, $index);
        }
        return $matchObjects;
    }

    public function mapToDetailObject(RawMatchesOffset $matches, int $index): MatchDetail
    {
        return DeprecatedMatchDetail::create($this->subject,
            $index,
            new RawMatchesToMatchAdapter($matches, $index),
            new EagerMatchAllFactory($matches),
            new MatchesFirstPrime($matches));
    }
}
