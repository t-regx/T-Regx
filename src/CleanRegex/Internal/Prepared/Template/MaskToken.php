<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Template\Mask\CompositeKeyword;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\Type\MaskType;
use TRegx\CleanRegex\Internal\Type\Type;

class MaskToken implements Token
{
    use DelimiterAware;

    /** @var CompositeKeyword */
    private $compositeKeyword;
    /** @var string[] */
    private $keywordsAndPatterns;

    public function __construct(string $mask, array $keywordsAndPatterns)
    {
        $this->compositeKeyword = new CompositeKeyword($mask, $keywordsAndPatterns);
        $this->keywordsAndPatterns = $keywordsAndPatterns;
    }

    public function word(): Word
    {
        return $this->compositeKeyword->word();
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
