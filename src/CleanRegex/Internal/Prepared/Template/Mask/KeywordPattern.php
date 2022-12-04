<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\Pattern\PatternAutoCapture;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Pattern\SubpatternFlagsStringPattern;
use TRegx\CleanRegex\Internal\Prepared\PatternPhrase;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Placeholders\LiteralPlaceholders;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

class KeywordPattern
{
    /** @var Flags */
    private $flags;
    /** @var Candidates */
    private $candidates;
    /** @var PatternPhrase */
    private $patternPhrase;
    /** @var string */
    private $pattern;
    /** @var string */
    private $keyword;
    /** @var PatternAutoCapture */
    private $autoCapture;

    public function __construct(Flags $flags, string $keyword, string $pattern, AutoCapture $autoCapture, SubpatternFlags $subpatternFlags)
    {
        $this->flags = $flags;
        $this->candidates = new Candidates(new UnsuitableStringCondition($pattern));
        $patternString = new SubpatternFlagsStringPattern($pattern, $subpatternFlags);
        $this->patternPhrase = new PatternPhrase($autoCapture, $patternString, new LiteralPlaceholders());
        $this->pattern = $pattern;
        $this->keyword = $keyword;
        $this->autoCapture = $autoCapture;
    }

    public function phrase(): Phrase
    {
        return $this->validPhrase($this->entitiesPhrase(), $this->delimiter());
    }

    private function validPhrase(Phrase $phrase, Delimiter $delimiter): Phrase
    {
        $definition = new Definition($delimiter->delimited($this->autoCapture, $phrase, $this->flags));
        if ($definition->valid()) {
            return $phrase;
        }
        throw new MaskMalformedPatternException("Malformed pattern '$this->pattern' assigned to keyword '$this->keyword'");
    }

    private function entitiesPhrase(): Phrase
    {
        try {
            return $this->patternPhrase->phrase();
        } catch (TrailingBackslashException $exception) {
            throw new MaskMalformedPatternException("Malformed pattern '$this->pattern' assigned to keyword '$this->keyword'");
        }
    }

    private function delimiter(): Delimiter
    {
        try {
            return $this->candidates->delimiter();
        } catch (UndelimitablePatternException $exception) {
            throw new ExplicitDelimiterRequiredException("mask pattern '$this->pattern' assigned to keyword '$this->keyword'");
        }
    }
}
