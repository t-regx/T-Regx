<?php
namespace Regex\Internal;

class OptionSettingExpression
{
    public string $pattern;
    public string $modifiers;

    public function __construct(string $pattern, string $modifiers)
    {
        $this->pattern = $this->pattern($pattern, $modifiers);
        $this->modifiers = \count_chars(\str_replace('n', '', $modifiers), 3);
    }

    private function pattern(string $pattern, string $modifiers): string
    {
        if (\strPos($modifiers, 'n') === false) {
            return $pattern;
        }
        return $this->patternNoAutoCapture($pattern, $this->optionSettingOffset($pattern));
    }

    private function optionSettingOffset(string $pattern): int
    {
        \preg_match("/^(\(\*[A-Z_]+(?:=\d+)?\))*/", $pattern, $match);
        return \strLen($match[0]);
    }

    private function patternNoAutoCapture(string $pattern, int $offset): string
    {
        return \subStr_replace($pattern, '(?n)', $offset, 0);
    }
}
