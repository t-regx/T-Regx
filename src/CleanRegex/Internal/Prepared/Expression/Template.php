<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Cluster\CountedClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\ClustersPlaceholders;

class Template implements Expression
{
    use PredefinedExpression;

    /** @var PatternPhrase */
    private $pattern;
    /** @var Spelling */
    private $spelling;

    public function __construct(Spelling $spelling, CountedClusters $clusters)
    {
        $this->pattern = new PatternPhrase($spelling, new ClustersPlaceholders(new ExpectedClusters($clusters)));
        $this->spelling = $spelling;
    }

    protected function phrase(): Phrase
    {
        return $this->pattern->phrase();
    }

    protected function delimiter(): Delimiter
    {
        try {
            return $this->spelling->delimiter();
        } catch (UndelimitablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forTemplate();
        }
    }

    protected function flags(): Flags
    {
        return $this->spelling->flags();
    }

    protected function undevelopedInput(): string
    {
        return $this->spelling->undevelopedInput();
    }
}
