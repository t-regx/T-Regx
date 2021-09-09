<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Delimiter\UndelimiterablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\StrictInterpretation;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\QuotableTemplate;

class Template implements Expression
{
    use StrictInterpretation;

    /** @var QuotableTemplate */
    private $template;
    /** @var Spelling */
    private $spelling;

    public function __construct(Spelling $spelling, CountedFigures $figures)
    {
        $this->spelling = $spelling;
        $this->template = new QuotableTemplate($spelling, $figures);
    }

    protected function quotable(): Quotable
    {
        try {
            return $this->template->quotable();
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }

    protected function delimiter(): Delimiter
    {
        try {
            return $this->spelling->delimiter();
        } catch (UndelimiterablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forTemplate();
        }
    }

    protected function flags(): Flags
    {
        return $this->spelling->flags();
    }

    protected function undevelopedInput(): string
    {
        return $this->spelling->undevelopedInput();
    }
}
