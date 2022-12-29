<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\ShiftString;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class PatternEntities
{
    /** @var PcreParser */
    private $pcreParser;

    public function __construct(StringPattern       $pattern,
                                GroupAutoCapture    $autoCapture,
                                PlaceholderConsumer $placeholderConsumer)
    {
        $this->pcreParser = new PcreParser(
            new ShiftString($pattern->pattern()),
            $pattern->subpatternFlags(),
            $autoCapture,
            $placeholderConsumer,
            new Convention($pattern->pattern()));
    }

    /**
     * @return Phrase[]
     */
    public function phrases(): array
    {
        return $this->pcreParser->phrases();
    }
}
