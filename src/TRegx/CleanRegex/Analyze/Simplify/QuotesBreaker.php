<?php
namespace TRegx\CleanRegex\Analyze\Simplify;

use TRegx\SafeRegex\preg;

class QuotesBreaker
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function split(): array
    {
        return $this->joinQuotes($this->splitQuotesAndEscapes());
    }

    private function splitQuotesAndEscapes(): array
    {
        return preg::split('/(\\\\.|\[|\])/', $this->pattern, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }

    private function joinQuotes(array $split): array
    {
        $result = [];
        $current = '';
        $quoting = false;

        foreach ($split as $item) {
            if ($quoting) {
                $current .= $item;
                if ($item === '\E') {
                    $quoting = false;
                    $result[] = $current;
                }
            } else {
                if ($item === '\Q') {
                    $quoting = true;
                    $current = $item;
                } else {
                    $result[] = $item;
                }
            }
        }

        if ($quoting) {
            $result[] = $current;
        }

        return $result;
    }
}
