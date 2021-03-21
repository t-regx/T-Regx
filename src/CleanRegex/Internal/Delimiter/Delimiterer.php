<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;

class Delimiterer
{
    /** @var Delimiters */
    private $delimiters;
    /** @var DelimiterParser */
    private $parser;
    /** @var DelimiterStrategy */
    private $delimiterStrategy;
    /** @var DelimiterFinder */
    private $finder;

    public function __construct(DelimiterStrategy $strategy)
    {
        $this->delimiters = new Delimiters();
        $this->parser = new DelimiterParser();
        $this->delimiterStrategy = $strategy;
        $this->finder = new DelimiterFinder();
    }

    public function delimiter(string $pattern): string
    {
        return $this->delimiterStrategy->buildPattern($pattern, $this->getDelimiter($pattern));
    }

    private function getDelimiter(string $pattern): ?string
    {
        if ($this->delimiterStrategy->shouldGuessDelimiter()) {
            return $this->finder->chooseDelimiter($pattern);
        }
        return $this->parser->getDelimiter($pattern);
    }
}
