<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;

class Delimiterer
{
    /** @var Delimiters */
    private $delimiters;
    /** @var DelimiterParser */
    private $parser;
    /** @var DelimiterStrategy */
    private $delimiterStrategy;

    public function __construct(DelimiterStrategy $strategy)
    {
        $this->delimiters = new Delimiters();
        $this->parser = new DelimiterParser();
        $this->delimiterStrategy = $strategy;
    }

    public function delimiter(string $pattern): string
    {
        return $this->delimiterStrategy->buildPattern($pattern, $this->getDelimiter($pattern));
    }

    private function getDelimiter(string $pattern): ?string
    {
        if ($this->delimiterStrategy->shouldGuessDelimiter()) {
            return $this->chooseDelimiter($pattern);
        }
        return $this->parser->getDelimiter($pattern);
    }

    private function chooseDelimiter(string $pattern): string
    {
        foreach ($this->delimiters->getDelimiters() as $delimiter) {
            if (\strpos($pattern, $delimiter) === false) {
                return $delimiter;
            }
        }
        throw new ExplicitDelimiterRequiredException($pattern);
    }
}
