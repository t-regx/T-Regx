<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Composite\ChainedReplace;
use TRegx\CleanRegex\Internal\Predefinitions;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class PatternList
{
    /** @var Predefinitions */
    private $predefinitions;

    public function __construct(Predefinitions $predefinitions)
    {
        $this->predefinitions = $predefinitions;
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
        return $this->chainedReplace($subject)->withReferences('');
    }

    public function chainedReplace(string $subject): ChainedReplace
    {
        return new ChainedReplace($this->predefinitions, new Subject($subject));
    }
}
