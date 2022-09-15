<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Prepared\Cluster\CountedClusters;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\ClustersPlaceholders;

class Template implements Expression
{
    /** @var DelimiterExpression */
    private $expression;

    public function __construct(Spelling $spelling, CountedClusters $clusters)
    {
        $this->expression = new DelimiterExpression($spelling, new ClustersPlaceholders($clusters));
    }

    public function predefinition(): Predefinition
    {
        try {
            return $this->expression->predefinition();
        } catch (UndelimitablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forTemplate();
        }
    }
}
