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
        return \substr($this->getMessage(), 0, 5) === 'preg_';
    }
}
