<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\CleanRegex\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\IdentityStrategy;

class Delimiterer
{
    /** @var Delimiters */
    private $delimiters;
    /** @var DelimiterParser */
    private $parser;
    /** @var DelimiterStrategy */
    private $delimiterStrategy;

    public function __construct(DelimiterStrategy $strategy = null)
    {
        $this->delimiters = new Delimiters();
        $this->parser = new DelimiterParser();
        $this->delimiterStrategy = $strategy ?? new IdentityStrategy();
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
        $delimiterNext = $this->getPossibleDelimiter($pattern);
        if ($delimiterNext !== null) {
            return $delimiterNext;
        }
        throw new ExplicitDelimiterRequiredException($pattern);
    }

    private function getPossibleDelimiter(string $pattern): ?string
    {
        foreach ($this->delimiters->getDelimiters() as $delimiter) {
            if (\strpos($pattern, $delimiter) === false) {
                return $delimiter;
            }
        }
        return null;
    }
}
