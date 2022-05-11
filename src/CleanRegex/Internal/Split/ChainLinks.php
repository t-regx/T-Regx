<?php
namespace TRegx\CleanRegex\Internal\Split;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\SafeRegex\preg;

class ChainLinks
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    public function links(string $subject): array
    {
        return $this->pregSplitElements($subject, -1)->elements();
    }

    public function linksFromStart(string $subject, int $maxSplits): array
    {
        return $this->pregSplitElements($subject, $maxSplits + 1)->elements();
    }

    public function linksFromEnd(string $subject, int $maxSplits): array
    {
        return $this->chainAndLinks($this->pregSplitSubject($subject, -1), $maxSplits);
    }

    private function pregSplitSubject(string $subject, int $limit): SplitSubject
    {
        return new SplitSubject($this->pregSplitElements($subject, $limit), $subject);
    }

    private function pregSplitElements(string $subject, int $limit): SplitElements
    {
        $elements = preg::split($this->definition->pattern, $subject, $limit,
            \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_OFFSET_CAPTURE);
        return new SplitElements($elements);
    }

    private function chainAndLinks(SplitSubject $subject, int $maxSplits): array
    {
        $index = $this->startingIndex($subject, $maxSplits);
        if ($index > 1) {
            return $subject->chainAndLinks($index);
        }
        return $subject->links();
    }

    private function startingIndex(SplitSubject $subject, int $maxSplits): int
    {
        return $subject->size() - $maxSplits * $this->groupsCount();
    }

    private function groupsCount(): int
    {
        \preg_match_all($this->definition->pattern, '', $structure);
        return \count($structure);
    }
}
