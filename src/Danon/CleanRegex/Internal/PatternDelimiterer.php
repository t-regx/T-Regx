<?php
namespace Danon\CleanRegex\Internal;

class PatternDelimiterer
{
    /** @var array */
    private $validDelimiters = ['/', '#', '%', '~', '+', '!'];

    public function delimiter(string $pattern): string
    {
        if ($this->isDelimitered($pattern)) {
            return $pattern;
        }

        return $this->tryDelimiter($pattern);
    }

    public function isDelimitered(string $pattern): bool
    {
        return $this->getDelimiter($pattern) !== null;
    }

    private function tryDelimiter(string $pattern): string
    {
        $delimiter = $this->getPossibleDelimiter($pattern);

        if ($delimiter === null) {
            throw new ExplicitDelimiterRequiredException($pattern);
        }

        return $delimiter . $pattern . $delimiter;
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

    public function getPossibleDelimiter(string $pattern): ?string
    {
        foreach ($this->validDelimiters as $delimiter) {
            if (strpos($pattern, $delimiter) === false) {
                return $delimiter;
            }
        }
        return null;
    }

    private function isValidDelimiter(string $character): bool
    {
        return in_array($character, $this->validDelimiters);
    }
}
