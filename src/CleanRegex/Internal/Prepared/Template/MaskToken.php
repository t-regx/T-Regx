<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use Generator;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Needles;
use TRegx\CleanRegex\Internal\Prepared\Word\CompositeWord;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\TrailingBackslash;
use TRegx\CleanRegex\Internal\Type\MaskType;
use TRegx\CleanRegex\Internal\Type\Type;
use TRegx\CleanRegex\Internal\ValidPattern;

class MaskToken implements Token
{
    use DelimiterAware;

    /** @var string */
    private $mask;
    /** @var array */
    private $keywords;
    /** @var Needles */
    private $needles;

    public function __construct(string $mask, array $keywords)
    {
        $this->mask = $mask;
        $this->keywords = $keywords;
        $this->needles = new Needles(\array_keys($this->keywords));
    }

    public function formatAsQuotable(): Word
    {
        foreach ($this->keywords as $keyword => $pattern) {
            $this->validatePair($pattern, $keyword);
        }
        foreach ($this->keywords as $keyword => $pattern) {
            $this->validateEmpty($keyword);
        }
        return new CompositeWord(\iterator_to_array($this->words()));
    }

    private function words(): Generator
    {
        foreach ($this->needles->split($this->mask) as $stringOrKeyword) {
            if (\array_key_exists($stringOrKeyword, $this->keywords)) {
                yield new PatternWord($this->keywords[$stringOrKeyword]);
            } else {
                yield new TextWord($stringOrKeyword);
            }
        }
    }

    private function validatePair(string $pattern, string $keyword): void
    {
        if (TrailingBackslash::hasTrailingSlash($pattern) || !ValidPattern::isValidStandard($pattern)) {
            throw new MaskMalformedPatternException("Malformed pattern '$pattern' assigned to keyword '$keyword'");
        }
    }

    public function validateEmpty(string $keyword): void
    {
        if ($keyword === '') {
            throw new \InvalidArgumentException("Keyword cannot be empty, must consist of at least one character");
        }
    }

    public function type(): Type
    {
        return new MaskType($this->keywords);
    }

    protected function delimiterAware(): string
    {
        return \implode($this->keywords);
    }
}
