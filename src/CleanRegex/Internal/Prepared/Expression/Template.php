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
use TRegx\CleanRegex\Internal\Prepared\Dictionary;
use TRegx\CleanRegex\Internal\Prepared\Figure\CountedFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class Template implements Expression
{
    use StrictInterpretation;

    /** @var Spelling */
    private $spelling;
    /** @var Dictionary */
    private $dictionary;

    public function __construct(Spelling $spelling, CountedFigures $figures)
    {
        $this->spelling = $spelling;
        $this->dictionary = new Dictionary($spelling, $figures);
    }

    protected function word(): Word
    {
        try {
            return $this->dictionary->compositeWord();
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
