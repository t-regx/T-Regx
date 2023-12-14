<?php
namespace Regex\Internal;

class ParsedPattern
{
    public ?string $errorMessage = null;
    private ?array $structure;

    public function __construct(string $delimited)
    {
        $this->parse($delimited);
        if ($this->errorMessage) {
            \preg_match('//', '');
        }
    }

    private function parse(string $delimited): void
    {
        \set_error_handler(function (int $type, string $message): void {
            $this->errorMessage = \subStr($message, \strLen('preg_match_all(): '));
        });
        @\preg_match_all($delimited, '', $this->structure);
        \restore_error_handler();
    }

    public function groupKeys(): array
    {
        return \array_keys($this->structure);
    }
}
