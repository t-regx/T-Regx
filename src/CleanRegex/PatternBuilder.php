<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Internal\Prepared\Format\IgnoreStrategy;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\FormatParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\Prepare;

class PatternBuilder
{
    /** @var bool */
    private $pcre;

    private function __construct(bool $pcre)
    {
        $this->pcre = $pcre;
    }

    public static function builder(): PatternBuilder
    {
        return new self(false);
    }

    public function pcre(): PatternBuilder
    {
        return new self(true);
    }

    /**
     * @param string $input
     * @param string[]|string[][] $values
     * @param string|null $flags
     * @return PatternInterface
     */
    public function bind(string $input, array $values, string $flags = null): PatternInterface
    {
        return Prepare::build(new BindingParser($input, $values, new IgnoreStrategy()), $this->pcre, $flags ?? '');
    }

    /**
     * @param string $input
     * @param string[]|string[][] $values
     * @param string|null $flags
     * @return PatternInterface
     */
    public function inject(string $input, array $values, string $flags = null): PatternInterface
    {
        return Prepare::build(new InjectParser($input, $values, new IgnoreStrategy()), $this->pcre, $flags ?? '');
    }

    /**
     * @param (string|string[])[] $input
     * @param string|null $flags
     * @return PatternInterface
     */
    public function prepare(array $input, string $flags = null): PatternInterface
    {
        return Prepare::build(new PreparedParser($input), $this->pcre, $flags ?? '');
    }

    /**
     * @param (string|PatternInterface)[] $patterns
     * @return CompositePattern
     */
    public static function compose(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());
    }

    public function format(string $pattern, array $tokens, string $flags = null): PatternInterface
    {
        return Prepare::build(new FormatParser($pattern, $tokens), $this->pcre, $flags ?? '');
    }

    public function template(string $pattern, string $flags = null): TemplatePattern
    {
        return new TemplatePattern($pattern, $flags ?? '', $this->pcre);
    }
}
