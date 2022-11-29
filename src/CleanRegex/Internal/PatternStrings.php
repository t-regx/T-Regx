<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Expression\Predefinition\IdentityPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
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

    public function predefinitions(): Predefinitions
    {
        $predefinitions = [];
        foreach ($this->patterns as $pattern) {
            $predefinitions[] = $this->predefinition($pattern);
        }
        return new Predefinitions($predefinitions);
    }

    private function predefinition($pattern): Predefinition
    {
        if (\is_string($pattern)) {
            $expression = new Standard(new StandardSpelling($pattern, Flags::empty(), new UnsuitableStringCondition($pattern)));
            return $expression->predefinition();
        }
        if ($pattern instanceof Pattern) {
            return new IdentityPredefinition(new Definition($pattern->delimited()));
        }
        throw InvalidArgument::typeGiven("PatternList can only compose type Pattern or string", new ValueType($pattern));
    }
}
