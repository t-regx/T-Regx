<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use Generator;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

class KeywordPatterns
{
    /** @var AutoCapture */
    private $autoCapture;
    /** @var Flags */
    private $flags;
    /** @var string[] */
    private $keywordPatterns;

    public function __construct(AutoCapture $autoCapture, Flags $flags, array $keywordPatterns)
    {
        $this->autoCapture = $autoCapture;
        $this->flags = $flags;
        $this->keywordPatterns = $keywordPatterns;
    }

    public function phrases(SubpatternFlags $subpatternFlags): array
    {
        $phrases = [];
        foreach ($this->patterns($subpatternFlags) as $keyword => $pattern) {
            $phrases[$keyword] = $pattern->phrase();
        }
        return $phrases;
    }

    private function patterns(SubpatternFlags $subpatternFlags): Generator
    {
        foreach ($this->keywordPatterns as $keyword => $pattern) {
            if ($keyword === '') {
                throw new \InvalidArgumentException("Keyword cannot be empty, must consist of at least one character");
            }
            yield $keyword => new KeywordPattern($this->flags, $keyword, $pattern, $this->autoCapture, $subpatternFlags);
        }
    }
}
