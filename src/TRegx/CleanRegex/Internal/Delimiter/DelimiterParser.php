<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use function strlen;
use function strrpos;

class DelimiterParser
{
    /** @var Delimiters */
    private $delimiters;

    public function __construct()
    {
        $this->delimiters = new Delimiters();
    }

    public function isDelimitered(string $pattern): bool
    {
        return $this->getDelimiter($pattern) !== null;
    }

    public function getDelimiter(string $pattern): ?string
    {
        if (strlen($pattern) < 2) {
            return null;
        }
        if ($this->delimiters->isValidDelimiter($pattern[0])) {
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
}
