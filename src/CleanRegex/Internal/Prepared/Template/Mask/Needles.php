<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

class Needles
{
    /** @var string[] */
    private $needles;

    public function __construct(array $needles)
    {
        $this->needles = $needles;
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
}
