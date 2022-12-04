<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
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

    public function __construct(AutoCapture $autoCapture, Spelling $spelling, CountedClusters $clusters)
    {
        $this->expression = new DelimiterExpression($autoCapture, $spelling, new ClustersPlaceholders($clusters));
    }

    public function predefinition(): Predefinition
    {
        try {
            return $this->expression->predefinition();
        } catch (UndelimitablePatternException $exception) {
            throw new ExplicitDelimiterRequiredException('template in its entirety');
        }
    }
}
