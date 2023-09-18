<?php
namespace Regex\Internal;

use Regex\RegexException;
use Regex\SyntaxException;
use Regex\UnicodeException;

class DelimitedExpression
{
    public string $delimited;
    /** @var int[]|string[] */
    public array $groupKeys;

    public function __construct(string $pattern, string $modifiers)
    {
        $delimiter = new Delimiter($pattern);
        $expression = new OptionSettingExpression($pattern, 'DX' . $modifiers);
        $this->delimited = $delimiter . $expression->pattern . $delimiter . $expression->modifiers;
        $parsed = new ParsedPattern($this->delimited);
        if ($parsed->errorMessage) {
            throw $this->exception($this->compilationFailed($parsed->errorMessage));
        }
        $this->groupKeys = $parsed->groupKeys();
    }

    private function exception(string $message): RegexException
    {
        if (\subStr($message, 0, 13) === 'UTF-8 error: ') {
            $unicodeMessage = \subStr($message, 13);
            return new UnicodeException("Malformed regular expression, $unicodeMessage.");
        }
        return new SyntaxException(\ucFirst($this->duplicateNames($message)) . '.');
    }

    private function compilationFailed(string $message): string
    {
        return \str_replace('Compilation failed: ', '', $message);
    }

    private function duplicateNames(string $message): string
    {
        return \str_replace('name (PCRE2_DUPNAMES not set) at', 'name at', $message);
    }
}
