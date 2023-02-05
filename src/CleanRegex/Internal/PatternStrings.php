<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\IdentityPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Pattern;

class PatternStrings
{
    /** @var AutoCapture */
    private $autoCapture;
    /** @var Flags */
    private $flags;
    /** @var (string|Pattern)[] */
    private $patterns;

    public function __construct(AutoCapture $autoCapture, array $patterns)
    {
        $this->autoCapture = $autoCapture;
        $this->flags = new Flags('');
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

    /**
     * @param string|Pattern $pattern
     */
    private function predefinition($pattern): Predefinition
    {
        if (\is_string($pattern)) {
            return $this->expression($pattern)->predefinition();
        }
        if ($pattern instanceof Pattern) {
            return new IdentityPredefinition(new Definition($pattern->delimited()));
        }
        throw InvalidArgument::typeGiven("PatternList can only compose type Pattern or string", new ValueType($pattern));
    }

    private function expression(string $pattern): Expression
    {
        return new Standard($this->autoCapture, new StandardSpelling($pattern, $this->flags, new UnsuitableStringCondition($pattern)));
    }
}
