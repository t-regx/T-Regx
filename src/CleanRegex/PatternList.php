<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\PatternStrings;
use TRegx\CleanRegex\Internal\Predefinitions;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\ChainedReplace;
use TRegx\SafeRegex\preg;

/**
 * @deprecated
 */
class PatternList
{
    /** @var Predefinitions */
    private $predefinitions;

    public function __construct(PatternStrings $patternStrings)
    {
        $this->predefinitions = $patternStrings->predefinitions();
    }

    /**
     * @deprecated
     */
    public function testAll(string $subject): bool
    {
        foreach ($this->predefinitions->definitions() as $definition) {
            if (!preg::match($definition->pattern, $subject)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @deprecated
     */
    public function testAny(string $subject): bool
    {
        foreach ($this->predefinitions->definitions() as $definition) {
            if (preg::match($definition->pattern, $subject)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @deprecated
     */
    public function failAll(string $subject): bool
    {
        return !$this->testAny($subject);
    }

    /**
     * @deprecated
     */
    public function failAny(string $subject): bool
    {
        return !$this->testAll($subject);
    }

    /**
     * @deprecated
     */
    public function prune(string $subject): string
    {
        return $this->replace($subject)->withReferences('');
    }

    /**
     * @deprecated
     */
    public function replace(string $subject): ChainedReplace
    {
        return new ChainedReplace($this->predefinitions, new Subject($subject));
    }

    /**
     * @deprecated
     */
    public function count(string $string): int
    {
        preg::replace($this->predefinitions->patternStrings(), '', $string, -1, $count);
        return $count;
    }
}
