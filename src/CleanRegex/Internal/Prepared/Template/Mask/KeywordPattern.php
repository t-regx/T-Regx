<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Mask;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\PatternAsEntities;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

class KeywordPattern
{
    /** @var Candidates */
    private $candidates;
    /** @var PatternAsEntities */
    private $patternAsEntities;
    /** @var string */
    private $pattern;
    /** @var string */
    private $keyword;

    public function __construct(string $keyword, string $pattern)
    {
        $this->candidates = new Candidates(new UnsuitableStringCondition($pattern));
        $this->patternAsEntities = new PatternAsEntities($pattern, Flags::empty(), new LiteralPlaceholderConsumer());
        $this->pattern = $pattern;
        $this->keyword = $keyword;
    }

    public function phrase(): Phrase
    {
        return $this->validPhrase($this->entitiesPhrase(), $this->delimiter());
    }

    private function validPhrase(Phrase $phrase, Delimiter $delimiter): Phrase
    {
        $definition = new Definition($delimiter->delimited($phrase, Flags::empty()), '');
        if ($definition->valid()) {
            return $phrase;
        }
        throw new MaskMalformedPatternException("Malformed pattern '$this->pattern' assigned to keyword '$this->keyword'");
    }

    private function entitiesPhrase(): Phrase
    {
        try {
            return $this->patternAsEntities->phrase();
        } catch (TrailingBackslashException $exception) {
            throw new MaskMalformedPatternException("Malformed pattern '$this->pattern' assigned to keyword '$this->keyword'");
        }
    }

    private function delimiter(): Delimiter
    {
        try {
            return $this->candidates->delimiter();
        } catch (UndelimitablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forMaskKeyword($this->keyword, $this->pattern);
        }
    }
}
