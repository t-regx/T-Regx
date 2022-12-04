<?php
namespace TRegx\CleanRegex\Internal\Expression\Predefinition;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class DelimiterPredefinition implements Predefinition
{
    /** @var Definition */
    private $definition;

    public function __construct(PatternAutoCapture $autoCapture, Phrase $phrase, Delimiter $delimiter, Flags $flags)
    {
        $this->definition = new Definition($delimiter->delimited($autoCapture, $phrase, $flags));
    }

    public function definition(): Definition
    {
        if ($this->definition->containsNullByte()) {
            throw new PatternMalformedPatternException('Pattern may not contain null-byte');
        }
        return $this->definition;
    }

    public function valid(): bool
    {
        return $this->definition->valid();
    }
}
