<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;

class DelimiterFinder
{
    /** @var Delimiters */
    private $delimiters;

    public function __construct()
    {
        $this->delimiters = new Delimiters();
    }

    public function chooseDelimiter(string $delimiterable): string
    {
        foreach ($this->delimiters->getDelimiters() as $delimiter) {
            if (\strpos($delimiterable, $delimiter) === false) {
                return $delimiter;
            }
        }
        throw new ExplicitDelimiterRequiredException($delimiterable);
    }
}
