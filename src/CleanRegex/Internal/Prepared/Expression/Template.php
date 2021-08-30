<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\QuotableTemplate;

class Template implements Expression
{
    use StrictInterpretation;

    /** @var QuotableTemplate */
    private $template;
    /** @var Orthography */
    private $orthography;

    public function __construct(Orthography $orthography, CountedFigures $figures)
    {
        $this->template = new QuotableTemplate($orthography, $figures);
        $this->orthography = $orthography;
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
        return $this->orthography->delimiter();
    }

    protected function flags(): Flags
    {
        return $this->orthography->flags();
    }

    protected function undevelopedInput(): string
    {
        return $this->orthography->undevelopedInput();
    }
}
