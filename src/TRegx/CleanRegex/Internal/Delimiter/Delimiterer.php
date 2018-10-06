<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use function strpos;

class Delimiterer
{
    /** @var DelimiterParser */
    private $parser;

    public function __construct(DelimiterParser $parser)
    {
        $this->parser = $parser;
    }

    public function delimiter(string $pattern): string
    {
        if ($this->parser->isDelimitered($pattern)) {
            return $pattern;
        }
        return $this->tryDelimiter($pattern);
    }

    private function tryDelimiter(string $pattern): string
    {
        $delimiter = $this->getPossibleDelimiter($pattern);
        if ($delimiter === null) {
            throw new ExplicitDelimiterRequiredException($pattern);
        }
        return $delimiter . $pattern . $delimiter;
    }

    public function getPossibleDelimiter(string $pattern): ?string
    {
        foreach ($this->parser->getDelimiters() as $delimiter) {
            if (strpos($pattern, $delimiter) === false) {
                return $delimiter;
            }
        }
        return null;
    }
}
