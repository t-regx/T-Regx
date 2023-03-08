<?php
namespace TRegx\CleanRegex\Internal\Split;

class SplitElements
{
    /** @var array{string, int}[] */
    private $elements;

    /**
     * @param array{string, int}[] $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return (string|null)[]
     * @phpstan-return list<string|null>
     */
    public function elements(): array
    {
        $elements = [];
        foreach ($this->elements as [$text, $offset]) {
            if ($offset === -1) {
                $elements[] = null;
            } else {
                $elements[] = $text;
            }
        }
        return $elements;
    }

    public function count(): int
    {
        return \count($this->elements);
    }

    /**
     * @return array{string, int}
     */
    public function entryAt(int $index): array
    {
        return $this->elements[$index];
    }
}
