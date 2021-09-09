<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;

class PcreString
{
    /** @var string */
    private $pattern;
    /** @var string */
    private $flags;
    /** @var string */
    private $delimiter;

    public function __construct(string $pcre, PcreDelimiterPredicate $predicate)
    {
        [$this->delimiter, $remainder] = $this->openingDelimiter($pcre);
        if (!$predicate->test($this->delimiter)) {
            throw MalformedPcreTemplateException::invalidDelimiter($this->delimiter);
        }
        [$this->pattern, $this->flags] = $this->patternAndModifiers($remainder, $this->delimiter);
    }

    private function openingDelimiter(string $pcre): array
    {
        if ($pcre === '') {
            throw MalformedPcreTemplateException::emptyPattern();
        }
        $cLikePcre = \ltrim($pcre, " \t\f\n\r\v");
        return [$cLikePcre[0], \substr($cLikePcre, 1)];
    }

    private function patternAndModifiers(string $pcre, string $delimiter): array
    {
        return $this->splitAtPosition($pcre, $this->lastOccurrence($pcre, $delimiter));
    }

    private function lastOccurrence(string $pcre, string $delimiter): int
    {
        $position = \strrpos($pcre, $delimiter);
        if ($position === false) {
            throw MalformedPcreTemplateException::unclosed($delimiter);
        }
        return $position;
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    public function flags(): string
    {
        return \str_replace([' ', "\n", "\r"], '', $this->flags);
    }

    public function delimiter(): string
    {
        return $this->delimiter;
    }

    private function splitAtPosition(string $string, int $position): array
    {
        $before = \substr($string, 0, $position);
        $after = \substr($string, $position + 1);
        return [$before, $after];
    }
}
