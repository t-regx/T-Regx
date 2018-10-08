<?php
namespace TRegx\CleanRegex\Match\Matches;

class PredefinedMatches implements Matches
{
    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function getMatches(): array
    {
        return $this->matches;
    }
}
