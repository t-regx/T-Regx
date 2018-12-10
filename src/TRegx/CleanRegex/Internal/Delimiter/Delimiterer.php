<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\CleanRegex\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\DelimiterStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\IdentityDelimiterStrategy;

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
        $this->delimiterStrategy = $strategy ?? new IdentityDelimiterStrategy();
    }

    public function delimiter(string $pattern): string
    {
        $delimiter = $this->parser->getDelimiter($pattern);
        if ($delimiter !== null) {
            return $this->delimiterStrategy->alreadyDelimitered($pattern, $delimiter);
        }

        $delimiterNext = $this->getPossibleDelimiter($pattern);
        if ($delimiterNext !== null) {
            return $this->delimiterStrategy->delimiter($pattern, $delimiterNext);
        }

        throw new ExplicitDelimiterRequiredException($pattern);
    }

    public function getPossibleDelimiter(string $pattern): ?string
    {
        foreach ($this->delimiters->getDelimiters() as $delimiter) {
            if (strpos($pattern, $delimiter) === false) {
                return $delimiter;
            }
        }
        return null;
    }
}
