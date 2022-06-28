<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\KeywordsCondition;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;
use TRegx\CleanRegex\Internal\Type\MaskType;
use TRegx\CleanRegex\Internal\Type\Type;

class MaskToken implements Token
{
    /** @var KeywordsCondition */
    private $condition;
    /** @var MaskPhrase */
    private $phrase;
    /** @var string[] */
    private $keywordsAndPatterns;

    public function __construct(string $mask, array $keywordsAndPatterns)
    {
        $this->condition = new KeywordsCondition($keywordsAndPatterns);
        $this->phrase = new MaskPhrase($mask, $keywordsAndPatterns);
        $this->keywordsAndPatterns = $keywordsAndPatterns;
    }

    public function suitable(string $candidate): bool
    {
        return $this->condition->suitable($candidate);
    }

    public function phrase(): Phrase
    {
        return $this->phrase->phrase();
    }

    public function type(): Type
    {
        return new MaskType($this->keywordsAndPatterns);
    }
}
