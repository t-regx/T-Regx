<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Prepared\Quoteable\Factory\Alternator;
use TRegx\SafeRegex\preg;

class MultiSplitter
{
    /** @var string */
    private $input;
    /** @var string[] */
    private $needles;

    public function __construct(string $input, array $needles)
    {
        $this->input = $input;
        $this->needles = $this->descendingLength($needles);
    }

    public function split(): array
    {
        if (empty($this->needles)) {
            return [$this->input];
        }
        $quotes = Alternator::quoteCapturing($this->needles, '/');
        return preg::split("/$quotes/", $this->input, -1, \PREG_SPLIT_DELIM_CAPTURE);
    }

    private function descendingLength(array $needles): array
    {
        \usort($needles, static function (string $a, string $b): int {
            return \strlen($b) - \strlen($a);
        });
        return $needles;
    }
}
