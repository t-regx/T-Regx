<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\AutoCapture\AutoCapture;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\KeywordsCondition;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;

class Mask implements Expression
{
    /** @var MaskPhrase */
    private $phrase;
    /** @var Candidates */
    private $candidates;
    /** @var AutoCapture */
    private $autoCapture;
    /** @var SubpatternFlags */
    private $subpatternFlags;
    /** @var string[] */
    private $keywords;
    /** @var Flags */
    private $flags;

    public function __construct(AutoCapture $autoCapture, string $mask, Flags $flags, array $keywords)
    {
        $this->phrase = new MaskPhrase($autoCapture, $mask, $flags, $keywords);
        $this->candidates = new Candidates(new KeywordsCondition($keywords));
        $this->autoCapture = $autoCapture;
        $this->subpatternFlags = SubpatternFlags::from($flags);
        $this->keywords = $keywords;
        $this->flags = $flags;
    }

    public function predefinition(): Predefinition
    {
        return new DelimiterPredefinition(
            $this->autoCapture,
            $this->phrase->phrase($this->subpatternFlags),
            $this->delimiter(),
            $this->flags
        );
    }

    private function delimiter(): Delimiter
    {
        try {
            return $this->candidates->delimiter();
        } catch (UndelimitablePatternException $exception) {
            $message = 'mask keywords in their entirety: ' . \implode(', ', $this->keywords);
            throw new ExplicitDelimiterRequiredException($message);
        }
    }
}
