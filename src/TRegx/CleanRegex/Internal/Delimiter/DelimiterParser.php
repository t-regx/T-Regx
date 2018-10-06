<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\FlagsValidator;
use function in_array;
use function strlen;
use function strrpos;

class DelimiterParser
{
    /** @var array */
    private static $validDelimiters = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '='];

    /** @var FlagsValidator */
    private $flagsValidator;

    public function __construct(FlagsValidator $flagsValidator)
    {
        $this->flagsValidator = $flagsValidator;
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
        if ($this->isValidDelimiter($pattern[0])) {
            return $this->tryGetDelimiter($pattern);
        }
        return null;
    }

    private function tryGetDelimiter(string $pattern): ?string
    {
        $first = $pattern[0];
        $lastOffset = strrpos($pattern, $first);
        $nextOffset = strpos($pattern, $first, 1);
        if ($lastOffset !== $nextOffset) {
            return null;
        }
        if (!$this->hasValidFlagsAfterOffset($pattern, $lastOffset)) {
            return null;
        }
        if ($lastOffset <= 0) {
            return null;
        }
        return $first;
    }

    private function isValidDelimiter(string $character): bool
    {
        return in_array($character, self::$validDelimiters);
    }

    public function getDelimiters(): array
    {
        return self::$validDelimiters;
    }

    private function hasValidFlagsAfterOffset(string $pattern, $lastOffset): bool
    {
        $flags = substr($pattern, $lastOffset + 1);
        return $this->flagsValidator->isValid($flags);
    }
}
