<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use TRegx\CleanRegex\Internal\PatternPrefix;

class Convention
{
    /** @var PatternPrefix */
    private $pattern;
    /** @var string[][] */
    private $lineEndings;

    public function __construct(string $pattern)
    {
        $this->pattern = new PatternPrefix($pattern);
        $this->lineEndings = [
            'CR'      => ["\r"],
            'LF'      => ["\n"],
            'CRLF'    => ["\r\n"],
            'ANYCRLF' => ["\r\n", "\r", "\n"],
            'ANY'     => ["\r\n", "\r", "\n", "\v", "\f", "\xc2\x85"],
            'NUL'     => ["\0"],
        ];
    }

    public function lineEndings(): array
    {
        $verbs = $this->pattern->internalOptions();
        while (!empty($verbs)) {
            $result = \array_pop($verbs);
            if (\array_key_exists($result, $this->lineEndings)) {
                return $this->lineEndings[$result];
            }
        }
        return ["\n"];
    }

}
