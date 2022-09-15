<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Identity;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Pattern;

class PatternStrings
{
    /** @var array */
    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function predefinitions(callable $patternDefinition): Predefinitions
    {
        $predefinitions = [];
        foreach ($this->expressions($patternDefinition) as $expression) {
            $predefinitions[] = $expression->predefinition();
        }
        return new Predefinitions($predefinitions);
    }

    private function expressions(callable $patternDefinition): Generator
    {
        foreach ($this->patterns as $pattern) {
            yield $this->expression($pattern, $patternDefinition);
        }
    }

    private function expression($pattern, callable $predefinition): Expression
    {
        if (\is_string($pattern)) {
            return new Standard(new StandardSpelling($pattern, Flags::empty(), new UnsuitableStringCondition($pattern)));
        }
        if ($pattern instanceof Pattern) {
            return new Identity($predefinition($pattern));
        }
        throw InvalidArgument::typeGiven("PatternList can only compose type Pattern or string", new ValueType($pattern));
    }
}
