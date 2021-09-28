<?php
namespace TRegx\CleanRegex\Composite;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\SafeRegex\preg;

class CompositePattern
{
    /** @var Definition[] */
    private $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function testAll(string $subject): bool
    {
        foreach ($this->definitions as $definition) {
            if (!preg::match($definition->pattern, $subject)) {
                return false;
            }
        }
        return true;
    }

    public function testAny(string $subject): bool
    {
        foreach ($this->definitions as $definition) {
            if (preg::match($definition->pattern, $subject)) {
                return true;
            }
        }
        return false;
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
        return new ChainedReplace($this->definitions, new StringSubject($subject), new DefaultStrategy());
    }
}
