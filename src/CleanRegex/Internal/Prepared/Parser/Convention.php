<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser;

use Generator;

class Convention
{
    /** @var string */
    private $pattern;
    /** @var string[][] */
    private $lineEndings = [
        'CR'      => ["\r"],
        'LF'      => ["\n"],
        'CRLF'    => ["\r\n"],
        'ANYCRLF' => ["\r\n", "\r", "\n"],
        'ANY'     => ["\r\n", "\r", "\n", "\v", "\f", "\xc2\x85"],
        'NUL'     => ["\0"],
    ];

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function lineEndings(): array
    {
        foreach ($this->prioritizedOptionNames() as $optionName) {
            if (\array_key_exists($optionName, $this->lineEndings)) {
                return $this->lineEndings[$optionName];
            }
        }
        return ["\n"];
    }

    private function prioritizedOptionNames(): Generator
    {
        $options = $this->internalOptions();
        for (\end($options); \key($options) !== null; \prev($options)) {
            yield \current($options);
        }
    }

    private function internalOptions(): array
    {
        \preg_match_all("/\(\*([A-Z_]+)=?[0-9]*\)/A", $this->pattern, $match);
        return $match[1];
    }
}
