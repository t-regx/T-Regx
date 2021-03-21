<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;

class Delimiterer
{
    /** @var Delimiters */
    private $delimiters;
    /** @var DelimiterStrategy */
    private $delimiterStrategy;

    public function __construct(DelimiterStrategy $strategy)
    {
        $this->delimiters = new Delimiters();
        $this->delimiterStrategy = $strategy;
    }

    public function delimiter(string $pattern): string
    {
        return $this->delimiterStrategy->buildPattern($pattern);
    }
}
