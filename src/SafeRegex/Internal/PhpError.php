<?php
namespace TRegx\SafeRegex\Internal;

class PhpError
{
    /** @var int */
    private $type;
    /** @var string */
    private $message;

    public function __construct(int $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isPregError(): bool
    {
        $pregMethods = [
            'preg_match()',
            'preg_match_all()',
            'preg_replace()',
            'preg_replace_callback()',
            'preg_replace_callback_array()',
            'preg_filter()',
            'preg_split()',
            'preg_grep()',
        ];
        foreach ($pregMethods as $pregMethod) {
            if ($this->startsWith($this->message, $pregMethod)) {
                return true;
            }
        }
        return false;
    }

    private function startsWith(string $string, string $needle): bool
    {
        return \subStr($string, 0, \strLen($needle)) === $needle;
    }
}
