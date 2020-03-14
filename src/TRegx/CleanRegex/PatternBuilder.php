<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

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
     * @param string[] $values
     * @param string $flags
     * @return PatternInterface
     */
    public function bind(string $input, array $values, string $flags = ''): PatternInterface
    {
        return $this->build(new BindingParser($input, $values), $flags);
    }

    /**
     * @param string $input
     * @param string[] $values
     * @param string $flags
     * @return PatternInterface
     */
    public function inject(string $input, array $values, string $flags = ''): PatternInterface
    {
        return $this->build(new InjectParser($input, $values), $flags);
    }

    /**
     * @param (string|string[])[] $input
     * @param string $flags
     * @return PatternInterface
     */
    public function prepare(array $input, string $flags = ''): PatternInterface
    {
        return $this->build(new PreparedParser($input), $flags);
    }

    /**
     * @param (string|PatternInterface)[] $patterns
     * @return CompositePattern
     */
    public static function compose(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());
    }

    private function build(Parser $parser, string $flags = ''): PatternInterface
    {
        return Pattern::pcre((new PrepareFacade($parser, $this->pcre, $flags))->getPattern() . $flags);
    }
}
