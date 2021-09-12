<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Needles;
use TRegx\CleanRegex\Internal\Prepared\Word\CompositeWord;
use TRegx\CleanRegex\Internal\Prepared\Word\PatternWord;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class CompositeKeyword
{
    /** @var string */
    private $mask;
    /** @var string[] */
    private $keywordPatterns;
    /** @var Needles */
    private $needles;

    public function __construct(string $mask, array $keywordPatterns)
    {
        $this->mask = $mask;
        $this->keywordPatterns = $keywordPatterns;
        $this->needles = new Needles(\array_keys($keywordPatterns));
    }

    public function word(): CompositeWord
    {
        return new CompositeWord($this->words());
    }

    private function words(): array
    {
        foreach ($this->keywordPatterns as $keyword => $keywordPattern) {
            if ($keyword === '') {
                throw new \InvalidArgumentException("Keyword cannot be empty, must consist of at least one character");
            }
            $pattern = new KeywordPattern($keywordPattern);
            if (!$pattern->valid()) {
                throw new MaskMalformedPatternException("Malformed pattern '$keywordPattern' assigned to keyword '$keyword'");
            }
        }
        return \array_map([$this, 'textOrPatternWord'], $this->needles->split($this->mask));
    }

    private function textOrPatternWord(string $stringOrKeyword): Word
    {
        if (\array_key_exists($stringOrKeyword, $this->keywordPatterns)) {
            return new PatternWord($this->keywordPatterns[$stringOrKeyword]);
        }
        return new TextWord($stringOrKeyword);
    }
}
