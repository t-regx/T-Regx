<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

class Convention
{
    /** @var string */
    private $pattern;
    /** @var string[][] */
    private $lineEndings;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->lineEndings = [
            '(*CR)'      => ["\r"],
            '(*LF)'      => ["\n"],
            '(*CRLF)'    => ["\r\n"],
            '(*ANYCRLF)' => ["\r\n", "\r", "\n"],
            '(*ANY)'     => ["\r\n", "\r", "\n", "\v", "\f", "\xc2\x85"],
            '(*NUL)'     => ["\0"],
        ];
    }

    public function lineEndings(): array
    {
        return $this->shiftedConventionEndings($this->pattern, ["\n"]);
    }

    private function shiftedConventionEndings(string $pattern, array $finalConventionEndings): array
    {
        foreach ($this->lineEndings as $verb => $endings) {
            if (\strPos($pattern, $verb) === 0) {
                return $this->shiftedConventionEndings(\subStr($pattern, \strLen($verb)), $endings);
            }
        }
        return $finalConventionEndings;
    }
}
