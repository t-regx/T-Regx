<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimiterablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Expression\StrictInterpretation;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class Mask implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $mask;
    /** @var array */
    private $keywords;
    /** @var string */
    private $flags;

    public function __construct(string $mask, array $keywords, string $flags)
    {
        $this->mask = $mask;
        $this->keywords = $keywords;
        $this->flags = $flags;
    }

    protected function word(): Word
    {
        return (new MaskToken($this->mask, $this->keywords))->formatAsQuotable();
    }

    protected function delimiter(): Delimiter
    {
        try {
            return Delimiter::suitable(\implode($this->keywords));
        } catch (UndelimiterablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forMask($this->keywords);
        }
    }

    protected function flags(): Flags
    {
        return new Flags($this->flags);
    }

    protected function undevelopedInput(): string
    {
        return $this->mask;
    }
}
