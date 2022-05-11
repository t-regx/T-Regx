<?php
namespace TRegx\CleanRegex\Internal\Split;

class SplitElements
{
    /** @var array */
    private $elements;

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

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

    public function entryAt(int $index): array
    {
        return $this->elements[$index];
    }
}
