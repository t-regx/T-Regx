<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;

class DelimiterFinder
{
    public function chooseDelimiter(string $delimiterable): string
    {
        foreach (Delimiters::getDelimiters() as $delimiter) {
            if (\strpos($delimiterable, $delimiter) === false) {
                return $delimiter;
            }
        }
        throw new ExplicitDelimiterRequiredException($delimiterable);
    }
}
