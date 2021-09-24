<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use Generator;

class KeywordPatterns
{
    /** @var string[] */
    private $keywordPatterns;

    public function __construct(array $keywordPatterns)
    {
        $this->keywordPatterns = $keywordPatterns;
    }

    public function phrases(): array
    {
        return \iterator_to_array($this->phrasesGenerator());
    }

    private function phrasesGenerator(): Generator
    {
        foreach ($this->patterns() as $keyword => $pattern) {
            yield $keyword => $pattern->phrase();
        }
    }

    private function patterns(): Generator
    {
        foreach ($this->keywordPatterns as $keyword => $pattern) {
            if ($keyword === '') {
                throw new \InvalidArgumentException("Keyword cannot be empty, must consist of at least one character");
            }
            yield $keyword => new KeywordPattern($keyword, $pattern);
        }
    }
}
