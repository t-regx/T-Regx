<?php
namespace TRegx\CleanRegex\Internal\Split;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Splits;
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

    public function linksFromStart(string $subject, Splits $splits): array
    {
        return $this->pregSplitElements($subject, $splits->elements())->elements();
    }

    public function linksFromEnd(string $subject, Splits $splits): array
    {
        return $this->chainAndLinks($this->pregSplitSubject($subject, -1), $splits);
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

    private function chainAndLinks(SplitSubject $subject, Splits $splits): array
    {
        $index = $this->startingIndex($subject, $splits);
        if ($index > 1) {
            return $subject->chainAndLinks($index);
        }
        return $subject->links();
    }

    private function startingIndex(SplitSubject $subject, Splits $splits): int
    {
        return $subject->size() - $splits->intValue() * $this->groupsCount();
    }

    private function groupsCount(): int
    {
        \preg_match_all($this->definition->pattern, '', $structure);
        return \count($structure);
    }
}
