<?php
namespace Regex\Internal;

class ParsedPattern
{
    public ?string $syntaxErrorMessage = null;

    public function __construct(string $delimited)
    {
        $this->parse($delimited);
        if ($this->syntaxErrorMessage) {
            \preg_match('//', '');
        }
    }

    private function parse(string $delimited): void
    {
        \set_error_handler(function (int $type, string $message): void {
            $this->syntaxErrorMessage = \subStr($message, \strLen('preg_match(): '));
        });
        @\preg_match($delimited, '');
        \restore_error_handler();
    }
}
