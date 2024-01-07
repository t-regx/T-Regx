<?php
namespace Regex\Internal;

use Regex\ExecutionException;
use Regex\RegexException;
use Regex\SyntaxException;
use Regex\UnicodeException;

class DelimitedExpression
{
    private string $pattern;
    private OptionSettingExpression $expression;

    public string $delimited;
    /** @var int[]|string[] */
    public array $groupKeys;

    public function __construct(string $pattern, Modifiers $modifiers)
    {
        $this->pattern = $pattern;
        $delimiter = new Delimiter($pattern);
        $this->expression = new OptionSettingExpression($pattern, $modifiers);
        $this->delimited = $delimiter . $this->expression . $delimiter . $modifiers;
        $parsed = new ParsedPattern($this->delimited);
        if ($parsed->errorMessage) {
            throw $this->exception($parsed->errorMessage);
        }
        $this->groupKeys = $parsed->groupKeys();
    }

    private function exception(string $message): RegexException
    {
        if (\subStr($message, 0, 20) === 'Compilation failed: ') {
            return $this->patternException(...\explode(' at offset ', \subStr($message, 20)));
        }
        if ($message === 'Null byte in regex') {
            return new SyntaxException($message, $this->pattern, \strPos($this->pattern, "\0"));
        }
        return new ExecutionException(\strTok($message, '.') . '.');
    }

    private function patternException(string $message, int $errorPosition): RegexException
    {
        if (\subStr($message, 0, 13) === 'UTF-8 error: ') {
            $unicodeMessage = \subStr($message, 13);
            return new UnicodeException("Malformed regular expression, $unicodeMessage, near position $errorPosition.");
        }
        return new SyntaxException(
            \ucFirst($this->duplicateNames($message)),
            $this->pattern,
            $this->expression->position($errorPosition));
    }

    private function duplicateNames(string $message): string
    {
        return \str_replace('name (PCRE2_DUPNAMES not set)', 'name', $message);
    }
}
