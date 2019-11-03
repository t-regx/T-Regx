<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\FlagsValidator;
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

    public function getDelimiter(string $pattern): ?string
    {
        if ($this->canBeDelimitered($pattern)) {
            return $this->tryGetDelimiter($pattern);
        }
        return null;
    }

    private function canBeDelimitered(string $pattern): bool
    {
        return strlen($pattern) >= 2 && $this->delimiters->isValidDelimiter($pattern[0]);
    }

    private function tryGetDelimiter(string $pattern): ?string
    {
        if ($this->endsWithFlags($pattern[0], $pattern)) {
            return $pattern[0];
        }
        return null;
    }

    private function endsWithFlags(string $delimiter, string $pattern): bool
    {
        $lastOffset = strrpos($pattern, $delimiter);
        return $lastOffset > 0 && $this->validFlags(\substr($pattern, $lastOffset + 1));
    }

    private function validFlags(string $flagString): bool
    {
        if ($flagString === '') {
            return true;
        }
        if (\preg_match('/^[a-zA-Z]*$/A', $flagString) === 0) {
            return false;
        }
        (new FlagsValidator())->validate($flagString);
        return true;
    }
}
