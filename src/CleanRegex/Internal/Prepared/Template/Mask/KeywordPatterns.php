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
        $phrases = [];
        foreach ($this->patterns() as $keyword => $pattern) {
            $phrases[$keyword] = $pattern->phrase();
        }
        return $phrases;
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
