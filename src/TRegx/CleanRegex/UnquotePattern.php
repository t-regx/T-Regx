<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;

class UnquotePattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function unquote(): string
    {
        return $this->unquoteStringWithCharacters($this->pattern->originalPattern, [
            '.', '\\', '+', '*', '?', '[', ']', '^', '$', '(', ')',
            '{', '}', '=', '!', '<', '>', '|', ':', '-', '#'
        ]);
    }

    private function unquoteStringWithCharacters(string $string, array $specialCharacters): string
    {
        return strtr($string, array_combine(array_map(function (string $char) {
            return '\\' . $char;
        }, $specialCharacters), $specialCharacters));
    }
}
