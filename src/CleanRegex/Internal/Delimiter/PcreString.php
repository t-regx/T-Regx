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
    /** @var PcreDelimiterPredicate */
    private $predicate;

    public function __construct(string $pcre, PcreDelimiterPredicate $predicate)
    {
        $this->predicate = $predicate;
        [$this->delimiter, $remainder] = $this->splitByFirstCharacter($pcre);
        [$this->pattern, $this->flags] = $this->splitByLastOccurrence($remainder, $this->delimiter);
    }

    private function splitByFirstCharacter(string $pcre): array
    {
        if ($pcre === '') {
            throw MalformedPcreTemplateException::emptyPattern();
        }
        if ($this->predicate->test($pcre[0])) {
            return [$pcre[0], \substr($pcre, 1)];
        }
        throw MalformedPcreTemplateException::invalidDelimiter($pcre[0]);
    }

    private function splitByLastOccurrence(string $pcre, string $delimiter): array
    {
        $position = $this->lastOccurrence($pcre, $delimiter);
        $pattern = \substr($pcre, 0, $position);
        $flags = \substr($pcre, $position + 1);
        return [$pattern, $flags];
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
        return $this->flags;
    }

    public function delimiter(): string
    {
        return $this->delimiter;
    }
}
