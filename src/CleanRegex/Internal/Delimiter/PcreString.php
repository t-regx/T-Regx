<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;

class PcreString
{
    /** @var PcreDelimiter */
    private $delimiter;
    /** @var string */
    private $pattern;
    /** @var string */
    private $flags;

    public function __construct(string $pcre)
    {
        [$this->delimiter, $undelimitedPcre] = $this->undelimitedWhitespacePcre($pcre);
        [$this->pattern, $this->flags] = $this->delimiter->patternAndFlags($undelimitedPcre);
    }

    private function undelimitedWhitespacePcre(string $whitespacePcre): array
    {
        return $this->undelimitedPcre(\lTrim($whitespacePcre, " \t\f\n\r\v"));
    }

    private function undelimitedPcre(string $pcre): array
    {
        if ($pcre === '') {
            throw new MalformedPcreTemplateException('pattern is empty');
        }
        return $this->shiftedDelimiter($pcre);
    }

    private function shiftedDelimiter(string $pcre): array
    {
        return [
            new PcreDelimiter($pcre[0]),
            \subStr($pcre, 1)
        ];
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
        return $this->delimiter->delimiter;
    }
}
