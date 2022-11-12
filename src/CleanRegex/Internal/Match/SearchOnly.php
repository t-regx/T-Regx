<?php
namespace TRegx\CleanRegex\Internal\Match;

class SearchOnly
{
    /** @var SearchBase */
    private $searchBase;

    public function __construct(SearchBase $base)
    {
        $this->searchBase = $base;
    }

    public function get(Limit $limit): array
    {
        if ($limit->empty()) {
            $this->searchBase->validate();
            return [];
        }
        if ($limit->intValue() === 1) {
            $text = $this->searchBase->matchFirstOrNull();
            if ($text === null) {
                return [];
            }
            return [$text];
        }
        return \array_slice($this->searchBase->matchAllTexts(), 0, $limit->intValue());
    }
}
