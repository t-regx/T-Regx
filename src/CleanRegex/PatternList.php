<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\PatternStrings;
use TRegx\CleanRegex\Internal\Predefinitions;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\ChainedReplace;
use TRegx\SafeRegex\preg;

class PatternList
{
    /** @var Predefinitions */
    private $predefinitions;

    public function __construct(PatternStrings $patternStrings)
    {
        $this->predefinitions = $patternStrings->predefinitions();
    }

    public function testAll(string $subject): bool
    {
        foreach ($this->predefinitions->definitions() as $definition) {
            if (!preg::match($definition->pattern, $subject)) {
                return false;
            }
        }
        return true;
    }

    public function testAny(string $subject): bool
    {
        foreach ($this->predefinitions->definitions() as $definition) {
            if (preg::match($definition->pattern, $subject)) {
                return true;
            }
        }
        return false;
    }

    public function failAll(string $subject): bool
    {
        return !$this->testAny($subject);
    }

    public function failAny(string $subject): bool
    {
        return !$this->testAll($subject);
    }

    public function prune(string $subject): string
    {
        return $this->replace($subject)->withReferences('');
    }

    public function replace(string $subject): ChainedReplace
    {
        return new ChainedReplace($this->predefinitions, new Subject($subject));
    }

    public function count(string $string): int
    {
        preg::replace($this->predefinitions->patternStrings(), '', $string, -1, $count);
        return $count;
    }
}
