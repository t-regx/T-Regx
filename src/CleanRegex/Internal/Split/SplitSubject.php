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

    /**
     * @return (string|null)[]
     * @phpstan-return list<string|null>
     */
    public function chainAndLinks(int $index): array
    {
        return \array_merge([$this->linkedChain($index)], $this->chainLinks($index));
    }

    private function linkedChain(int $index): string
    {
        return $this->substringEntryTail($this->subject, $this->elements->entryAt($index - 1));
    }

    /**
     * @return (string|null)[]
     * @phpstan-return list<string|null>
     */
    private function chainLinks(int $index): array
    {
        return \array_slice($this->elements->elements(), $index);
    }

    /**
     * @param array{string, int} $entry
     */
    private function substringEntryTail(string $subject, array $entry): string
    {
        return \subStr($subject, 0, $this->entryTail($entry));
    }

    /**
     * @param array{string, int} $entry
     */
    private function entryTail(array $entry): int
    {
        [$text, $offset] = $entry;
        return $offset + \strLen($text);
    }

    /**
     * @return (string|null)[]
     * @phpstan-return list<string|null>
     */
    public function links(): array
    {
        return $this->elements->elements();
    }

    public function size(): int
    {
        return $this->elements->count();
    }
}
