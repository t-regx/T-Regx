<?php
namespace TRegx\CleanRegex\Internal;

use Generator;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;

class Predefinitions
{
    /** @var Predefinition[] */
    private $predefinitions;

    public function __construct(array $predefinitions)
    {
        $this->predefinitions = $predefinitions;
    }

    public function definitions(): array
    {
        return \iterator_to_array($this->definitionsGenerator());
    }

    private function definitionsGenerator(): Generator
    {
        foreach ($this->predefinitions as $predefinition) {
            yield $predefinition->definition();
        }
    }
}
