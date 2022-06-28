<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;
use TRegx\CleanRegex\Internal\Type\MaskType;
use TRegx\CleanRegex\Internal\Type\Type;

class MaskToken implements Token
{
    use DelimiterAware;

    /** @var MaskPhrase */
    private $phrase;
    /** @var string[] */
    private $keywordsAndPatterns;

    public function __construct(string $mask, array $keywordsAndPatterns)
    {
        $this->phrase = new MaskPhrase($mask, $keywordsAndPatterns);
        $this->keywordsAndPatterns = $keywordsAndPatterns;
    }

    public function phrase(): Phrase
    {
        return $this->phrase->phrase();
    }

    public function type(): Type
    {
        return new MaskType($this->keywordsAndPatterns);
    }

    protected function delimiterAware(): string
    {
        return \implode($this->keywordsAndPatterns);
    }
}
