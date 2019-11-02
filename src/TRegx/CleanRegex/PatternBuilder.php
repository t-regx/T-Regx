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
    public static function builder()
    {
        return new self();
    }

    /**
     * @param string $input
     * @param string[] $values
     * @param string $flags
     * @return Pattern
     */
    public function bind(string $input, array $values, string $flags = ''): Pattern
    {
        return $this->build(new BindingParser($input, $values), $flags);
    }

    /**
     * @param string $input
     * @param string[] $values
     * @param string $flags
     * @return Pattern
     */
    public function inject(string $input, array $values, string $flags = ''): Pattern
    {
        return $this->build(new InjectParser($input, $values), $flags);
    }

    /**
     * @param (string|string[])[] $input
     * @param string $flags
     * @return Pattern
     */
    public function prepare(array $input, string $flags = ''): Pattern
    {
        return $this->build(new PreparedParser($input), $flags);
    }

    /**
     * @param (string|Pattern)[] $patterns
     * @return CompositePattern
     */
    public static function compose(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());
    }

    private function build(Parser $parser, string $flags = ''): Pattern
    {
        return new Pattern((new PrepareFacade($parser))->getPattern(), $flags);
    }
}
