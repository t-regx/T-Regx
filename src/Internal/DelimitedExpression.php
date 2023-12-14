<?php
namespace Regex\Internal;

use Regex\SyntaxException;

class DelimitedExpression
{
    public string $delimited;

    public function __construct(string $pattern)
    {
        $this->delimited = "/$pattern/";
        $errorMessage = $this->syntaxErrorMessage();
        if ($errorMessage) {
            throw new SyntaxException($this->exceptionMessage($errorMessage));
        }
    }

    private function syntaxErrorMessage(): ?string
    {
        $error = null;
        \set_error_handler(static function (int $type, string $message) use (&$error): void {
            $error = $message;
        });
        @\preg_match($this->delimited, '');
        return $error;
    }

    private function exceptionMessage(string $message): string
    {
        return \ucFirst(\subStr($message, \strLen('preg_match(): Compilation failed: '))) . '.';
    }
}
