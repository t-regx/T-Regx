<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\Details\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @deprecated
 */
class DetailObjectFactory
{
    /** @var Subject */
    private $subject;
    /** @var UserData */
    private $userData;

    public function __construct(Subject $subject, UserData $userData)
    {
        $this->subject = $subject;
        $this->userData = $userData;
    }

    public function mapToDetailObjects(RawMatchesOffset $matches): array
    {
        $matchObjects = [];
        foreach ($matches->matches[0] as $index => $firstWhole) {
            $matchObjects[$index] = DeprecatedMatchDetail::create($this->subject,
                $index,
                -1,
                new RawMatchesToMatchAdapter($matches, $index),
                new EagerMatchAllFactory($matches),
                $this->userData);
        }
        return $matchObjects;
    }
}
