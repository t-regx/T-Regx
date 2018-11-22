<?php
namespace TRegx\CleanRegex\Split;

interface SplitPatternInterface
{
    /**
     * @return string[]
     */
    public function ex(): array;

    /**
     * @return string[]
     */
    public function inc(): array;
}
