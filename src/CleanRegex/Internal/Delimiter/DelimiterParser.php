<?php
namespace CleanRegex\Internal\Delimiter;

class DelimiterParser
{
    /** @var array */
    private $validDelimiters = ['/', '#', '%', '~', '+', '!'];

    public function isDelimitered(string $pattern): bool
    {
        return $this->getDelimiter($pattern) !== null;
    }

    public function getDelimiter(string $pattern): ?string
    {
        if (strlen($pattern) < 2) {
            return null;
        }
        $firstLetter = $pattern[0];
        if ($this->isValidDelimiter($firstLetter)) {
            $lastOffset = strrpos($pattern, $firstLetter);
            if ($lastOffset === 0) {
                return null;
            }

            $flags = substr($pattern, $lastOffset);

            return $firstLetter;
        }

        return null;
    }

    public function isValidDelimiter(string $character): bool
    {
        return in_array($character, $this->validDelimiters);
    }

    public function getDelimiters(): array
    {
        return $this->validDelimiters;
    }
}
