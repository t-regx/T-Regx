<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\StrictInterpretation;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\KeywordsCondition;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;

class Mask implements Expression
{
    use StrictInterpretation;

    /** @var MaskPhrase */
    private $phrase;
    /** @var Candidates */
    private $candidates;
    /** @var Flags */
    private $flags;
    /** @var string[] */
    private $keywords;
    /** @var string */
    private $mask;

    public function __construct(string $mask, Flags $flags, array $keywords)
    {
        $this->phrase = new MaskPhrase($mask, $keywords);
        $this->candidates = new Candidates(new KeywordsCondition($keywords));
        $this->flags = $flags;
        $this->keywords = $keywords;
        $this->mask = $mask;
    }

    protected function phrase(): Phrase
    {
        return $this->phrase->phrase();
    }

    protected function delimiter(): Delimiter
    {
        try {
            return $this->candidates->delimiter();
        } catch (UndelimitablePatternException $exception) {
            $message = 'mask keywords in their entirety: ' . \implode(', ', $this->keywords);
            throw new ExplicitDelimiterRequiredException($message);
        }
    }

    protected function flags(): Flags
    {
        return $this->flags;
    }
}
