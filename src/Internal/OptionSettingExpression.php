<?php
namespace Regex\Internal;

class OptionSettingExpression
{
    private string $pattern;
    private Modifiers $modifiers;
    private ?int $optionSettings;

    public function __construct(string $pattern, Modifiers $modifiers)
    {
        if ($modifiers->autoCapture) {
            $this->optionSettings = $this->optionSettingOffset($pattern);
            $this->pattern = $this->patternNoAutoCapture($pattern, $this->optionSettings);
        } else {
            $this->pattern = $pattern;
        }
        $this->modifiers = $modifiers;
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

    public function position(int $position): int
    {
        if ($this->modifiers->autoCapture && $position > $this->optionSettings) {
            return $position - 4;
        }
        return $position;
    }

    public function __toString(): string
    {
        return $this->pattern;
    }
}
