<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

class DelimiterParser
{
    /** @var array */
    private static $validDelimiters = ['/', '#', '%', '~', '+', '!'];

    public function isDelimitered(string $pattern): bool
    {
        return $this->getDelimiter($pattern) !== null;
    }

    public function getDelimiter(string $pattern): ?string
    {
        if (strlen($pattern) < 2) {
            return null;
        }
        if ($this->isValidDelimiter($pattern[0])) {
            return $this->tryGetDelimiter($pattern);
        }
        return null;
    }

    private function tryGetDelimiter(string $pattern): ?string
    {
        $lastOffset = strrpos($pattern, $pattern[0]);
        if ($lastOffset > 0) {
            return $pattern[0];
        }
        return null;
    }

    private function isValidDelimiter(string $character): bool
    {
        return in_array($character, self::$validDelimiters);
    }

    public function getDelimiters(): array
    {
        return self::$validDelimiters;
    }
}
