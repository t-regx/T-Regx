<?php
namespace TRegx\CleanRegex\Internal\Split;

class SplitSubject
{
    /** @var SplitElements */
    private $elements;
    /** @var string */
    private $subject;

    public function __construct(SplitElements $elements, string $subject)
    {
        $this->elements = $elements;
        $this->subject = $subject;
    }

    public function chainAndLinks(int $index): array
    {
        return \array_merge([$this->linkedChain($index)], $this->chainLinks($index));
    }

    private function linkedChain(int $index): string
    {
        return $this->substringEntryTail($this->subject, $this->elements->entryAt($index - 1));
    }

    private function chainLinks(int $index): array
    {
        return \array_slice($this->elements->elements(), $index);
    }

    private function substringEntryTail(string $subject, array $entry): string
    {
        return \subStr($subject, 0, $this->entryTail($entry));
    }

    private function entryTail(array $entry): int
    {
        [$text, $offset] = $entry;
        return $offset + \strLen($text);
    }

    public function links(): array
    {
        return $this->elements->elements();
    }

    public function size(): int
    {
        return $this->elements->count();
    }
}
