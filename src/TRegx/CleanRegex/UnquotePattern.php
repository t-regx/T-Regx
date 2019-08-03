<?php
namespace TRegx\CleanRegex;

class UnquotePattern
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function unquote(): string
    {
        return $this->unquoteStringWithCharacters($this->pattern, [
            '.', '\\', '+', '*', '?', '[', ']', '^', '$', '(', ')',
            '{', '}', '=', '!', '<', '>', '|', ':', '-', '#'
        ]);
    }

    private function unquoteStringWithCharacters(string $string, array $specialCharacters): string
    {
        return \strtr($string, \array_combine(\array_map(function (string $char) {
            return '\\' . $char;
        }, $specialCharacters), $specialCharacters));
    }
}
