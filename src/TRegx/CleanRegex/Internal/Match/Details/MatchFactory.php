<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Match\Details\Match;

class MatchFactory
{
    private const GROUP_WHOLE_MATCH = 0;

    /**
     * @param Base  $base
     * @param array $matches
     * @return Match[]
     */
    public static function fromMatchAll(Base $base, array $matches): array
    {
        $matchObjects = [];
        foreach ($matches[self::GROUP_WHOLE_MATCH] as $index => $match) {
            $matchObjects[] = new Match($base->getSubject(), $index, $matches);
        }
        return $matchObjects;
    }
}
