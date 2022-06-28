<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Prepared\Template\DelimiterAware;

class KeywordsCondition implements Condition
{
    use DelimiterAware;

    /** @var string[] */
    private $keywordsAndPatterns;

    public function __construct(array $keywordsAndPatterns)
    {
        $this->keywordsAndPatterns = $keywordsAndPatterns;
    }

    protected function delimiterAware(): string
    {
        return \implode($this->keywordsAndPatterns);
    }
}
