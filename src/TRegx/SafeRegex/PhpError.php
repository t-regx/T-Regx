<?php
namespace TRegx\SafeRegex;

use function error_get_last;

class PhpError
{
    /** @var int */
    private $type;
    /** @var string */
    private $message;
    /** @var string */
    private $file;
    /** @var int */
    private $line;

    public function __construct(int $type, string $message, string $file, int $line)
    {
        $this->type = $type;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public static function fromArray(array $array): PhpError
    {
        return new self($array['type'], $array['message'], $array['file'], $array['line']);
    }

    public static function getLast(): ?PhpError
    {
        $error = error_get_last();
        if ($error === null) {
            return null;
        }
        return self::fromArray($error);
    }
}
