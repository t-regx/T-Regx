<?php
namespace TRegx\CleanRegex\Analyze\Simplify;

use InvalidArgumentException;

class CodePoint
{
    /** @var string */
    private $character;

    public function __construct(string $character)
    {
        $this->character = $character;
    }

    public function index(): int
    {
        if (mb_strlen($this->character) !== 1) {
            throw new InvalidArgumentException();
        }
        return $this->getCodePoint($this->character);
    }

    private function getCodePoint(string $string): int
    {
        $utf32 = mb_convert_encoding($string, 'UTF-32', 'UTF-8');
        return hexdec(bin2hex($utf32));
    }
}
