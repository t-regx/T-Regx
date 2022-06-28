<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Dictionary;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Template implements Expression
{
    use PredefinedExpression;

    /** @var Dictionary */
    private $dictionary;
    /** @var Spelling */
    private $spelling;

    public function __construct(Spelling $spelling, CountedFigures $figures)
    {
        $this->dictionary = new Dictionary($spelling, $figures);
        $this->spelling = $spelling;
    }

    protected function phrase(): Phrase
    {
        return $this->dictionary->compositePhrase();
    }

    protected function delimiter(): Delimiter
    {
        try {
            return $this->spelling->delimiter();
        } catch (UndelimitablePatternException $exception) {
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
