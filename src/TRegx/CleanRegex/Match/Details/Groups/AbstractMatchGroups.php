<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

use Closure;
use TRegx\CleanRegex\Internal\Grouper;

abstract class AbstractMatchGroups implements MatchGroups
{
    /** @var array */
    protected $matches;
    /** @var int */
    protected $index;

    protected function __construct(array $matches, int $index)
    {
        $this->matches = $matches;
        $this->index = $index;
    }

    /**
     * @return (string|null)[]
     */
    public function texts(): array
    {
        return $this->forEachGroup(function (Grouper $grouper) {
            return $grouper->getText();
        });
    }

    /**
     * @return (int|null)[]
     */
    public function offsets(): array
    {
        return $this->forEachGroup(function (Grouper $grouper) {
            return $grouper->getOffset();
        });
    }

    private function forEachGroup(Closure $grouperResolver): array
    {
        return $this->sliceWholeMatch($this->forEachMatch($grouperResolver));
    }

    protected function sliceWholeMatch(array $matches): array
    {
        return array_slice($matches, 1);
    }

    /**
     * @param Closure $resolver
     * @return Grouper[]
     */
    private function forEachMatch(Closure $resolver): array
    {
        return array_map(function (array $match) use ($resolver) {
            return $resolver(new Grouper($match[$this->index]));
        }, $this->getIndexMatches());
    }

    private function getIndexMatches(): array
    {
        return array_filter($this->matches, function (array $match, $groupIndexOrName) {
            return $this->filterGroupKey($groupIndexOrName);
        }, ARRAY_FILTER_USE_BOTH);
    }

    protected abstract function filterGroupKey($groupIndexOrName): bool;
}
