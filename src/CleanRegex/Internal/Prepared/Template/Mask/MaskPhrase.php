<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use Generator;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\UnconjugatedPhrase;
use TRegx\CleanRegex\Internal\Prepared\Word\TextWord;

class MaskPhrase
{
    /** @var string */
    private $mask;
    /** @var KeywordPatterns */
    private $patterns;
    /** @var Needles */
    private $needles;

    public function __construct(string $mask, array $keywordPatterns)
    {
        $this->mask = $mask;
        $this->patterns = new KeywordPatterns($keywordPatterns);
        $this->needles = new Needles(\array_keys($keywordPatterns));
    }

    public function phrase(): Phrase
    {
        return new CompositePhrase(\iterator_to_array($this->splitPhrases($this->patterns->phrases())));
    }

    private function splitPhrases(array $phrases): Generator
    {
        foreach ($this->needles->split($this->mask) as $value) {
            if (\array_key_exists($value, $phrases)) {
                yield $phrases[$value];
            } else {
                yield new UnconjugatedPhrase(new TextWord($value));
            }
        }
    }
}
