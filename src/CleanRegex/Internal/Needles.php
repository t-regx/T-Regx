<?php
namespace TRegx\CleanRegex\Internal;

class Needles
{
    /** @var string[] */
    private $needles;

    public function __construct(array $needles)
    {
        $this->needles = $this->descendingLength($needles);
    }

    public function split(string $string): array
    {
        if (empty($this->needles)) {
            return [$string];
        }
        return \preg_split($this->splitPattern(), $string, -1, \PREG_SPLIT_DELIM_CAPTURE);
    }

    private function splitPattern(): string
    {
        return '/(' . \implode('|', \array_map([$this, 'escape'], $this->needles)) . ')/';
    }

    private function escape(string $input): string
    {
        return \preg_quote($input, '/');
    }

    private function descendingLength(array $needles): array
    {
        \usort($needles, static function (string $a, string $b): int {
            return \strlen($b) - \strlen($a);
        });
        return $needles;
    }
}
