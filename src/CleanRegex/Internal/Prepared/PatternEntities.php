<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\ShiftString;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\Placeholders;

class PatternEntities
{
    /** @var PcreParser */
    private $pcreParser;

    public function __construct(StringPattern $pattern, GroupAutoCapture $autoCapture, Placeholders $placeholders)
    {
        $feed = new ShiftString($pattern->pattern());
        $this->pcreParser = new PcreParser(
            $feed,
            $pattern->subpatternFlags(),
            $autoCapture,
            $placeholders->consumer($feed),
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
