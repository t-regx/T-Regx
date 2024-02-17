<?php
namespace Regex\Internal;

use Regex\ExecutionException;
use Regex\RegexException;
use Regex\SyntaxException;
use Regex\UnicodeException;

class ExceptionFactory
{
    private string $input;
    private SeekableExpression $expression;

    public function __construct(string $input, SeekableExpression $expression)
    {
        $this->input = $input;
        $this->expression = $expression;
    }

    public function exceptionFor(string $message): RegexException
    {
        if (\subStr($message, 0, 20) === 'Compilation failed: ') {
            return $this->compilationException(\subStr($message, 20));
        }
        if ($message === 'Null byte in regex') {
            return $this->nullByteSyntaxException($message);
        }
        return $this->executionException($message);
    }

    private function compilationException(string $message): RegexException
    {
        return $this->compilationExceptionAtPosition(...\explode(' at offset ', $message));
    }

    private function compilationExceptionAtPosition(string $message, int $errorPosition): RegexException
    {
        if (\subStr($message, 0, 13) === 'UTF-8 error: ') {
            return $this->unicodeException(\subStr($message, 13), $errorPosition);
        }
        return $this->syntaxException($message, $errorPosition);
    }

    private function unicodeException(string $message, int $errorPosition): UnicodeException
    {
        return new UnicodeException("Malformed regular expression, $message, near position $errorPosition.");
    }

    private function syntaxException(string $message, int $errorPosition): SyntaxException
    {
        $syntaxError = \ucFirst($this->duplicateNames($message));
        return new SyntaxException($syntaxError, $this->input,
            $this->expression->position($errorPosition));
    }

    private function duplicateNames(string $message): string
    {
        return \str_replace('name (PCRE2_DUPNAMES not set)', 'name', $message);
    }

    private function nullByteSyntaxException(string $message): SyntaxException
    {
        return new SyntaxException($message, $this->input, $this->nullBytePosition());
    }

    private function nullBytePosition(): int
    {
        return \strPos($this->input, "\0");
    }

    private function executionException(string $message): ExecutionException
    {
        return new ExecutionException($this->firstSentence($message));
    }

    private function firstSentence(string $message): string
    {
        return \strTok($message, '.') . '.';
    }
}
