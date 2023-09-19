<?php
namespace Regex\Internal;

class OptionSettingExpression
{
    private string $pattern;

    public function __construct(string $pattern, Modifiers $modifiers)
    {
        if ($modifiers->autoCapture) {
            $this->pattern = $this->patternNoAutoCapture($pattern, $this->optionSettingOffset($pattern));
        } else {
            $this->pattern = $pattern;
        }
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

    public function __toString(): string
    {
        return $this->pattern;
    }
}
