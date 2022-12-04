<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\Predefinition\DelimiterPredefinition;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\KeywordsCondition;
use TRegx\CleanRegex\Internal\Prepared\Template\Mask\MaskPhrase;

class Mask implements Expression
{
    /** @var MaskPhrase */
    private $phrase;
    /** @var Candidates */
    private $candidates;
    /** @var string[] */
    private $keywords;
    /** @var Flags */
    private $flags;

    public function __construct(string $mask, Flags $flags, array $keywords)
    {
        $this->phrase = new MaskPhrase($mask, $flags, $keywords);
        $this->candidates = new Candidates(new KeywordsCondition($keywords));
        $this->keywords = $keywords;
        $this->flags = $flags;
    }

    public function predefinition(): Predefinition
    {
        return new DelimiterPredefinition(
            $this->phrase->phrase(),
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
