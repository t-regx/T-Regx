<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\PatternAsEntities;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\Type\PatternType;
use TRegx\CleanRegex\Internal\Type\Type;

class PatternToken implements Token
{
    use DelimiterAware;

    /** @var PatternAsEntities */
    private $patternAsEntities;
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->patternAsEntities = new PatternAsEntities($pattern, new Flags(''), new LiteralPlaceholderConsumer());
        $this->pattern = $pattern;
    }

    public function word(): Word
    {
        return $this->patternAsEntities->word();
    }

    public function type(): Type
    {
        return new PatternType($this->pattern);
    }

    protected function delimiterAware(): string
    {
        return $this->pattern;
    }
}
