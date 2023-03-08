<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;

class Predefinitions
{
    /** @var Predefinition[] */
    private $predefinitions;

    /**
     * @param Predefinition[] $predefinitions
     */
    public function __construct(array $predefinitions)
    {
        $this->predefinitions = $predefinitions;
    }

    /**
     * @return array<string>
     */
    public function patternStrings(): array
    {
        return \iterator_to_array($this->patternsGenerator());
    }

    /**
     * @return Generator<string>
     */
    private function patternsGenerator(): Generator
    {
        foreach ($this->definitionsGenerator() as $definition) {
            yield $definition->pattern;
        }
    }

    /**
     * @return Definition[]
     */
    public function definitions(): array
    {
        return \iterator_to_array($this->definitionsGenerator());
    }

    /**
     * @return Generator<Definition>
     */
    private function definitionsGenerator(): Generator
    {
        foreach ($this->predefinitions as $predefinition) {
            yield $predefinition->definition();
        }
    }
}
