<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\MatchDetail;

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
            $matchObjects[$index] = MatchDetail::create($this->subject,
                $index,
                -1,
                new RawMatchesToMatchAdapter($matches, $index),
                new EagerMatchAllFactory($matches),
                $this->userData);
        }
        return $matchObjects;
    }

    public function mapToDetailObjectsFiltered(RawMatchesOffset $matches, Predicate $predicate): array
    {
        $matchObjects = $this->mapToDetailObjects($matches);
        $filteredMatches = \array_filter($matchObjects, [$predicate, 'test']);

        return \array_map(static function (array $match) use ($filteredMatches): array {
            return \array_intersect_key($match, $filteredMatches);
        }, $matches->matches);
    }
}
