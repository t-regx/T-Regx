<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CharacterClassConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CommentConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Convention;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;

class PatternEntities
{
    /** @var PcreParser */
    private $pcreParser;

    public function __construct(StringPattern $pattern, PlaceholderConsumer $placeholderConsumer)
    {
        $this->pcreParser = new PcreParser(new Feed($pattern->pattern()), $pattern->subpatternFlags(), [
            new ControlConsumer(),
            new QuoteConsumer(),
            new EscapeConsumer(),
            new GroupConsumer(),
            new GroupCloseConsumer(),
            $placeholderConsumer,
            new CharacterClassConsumer(),
            new CommentConsumer(new Convention($pattern->pattern())),
            new LiteralConsumer(),
        ]);
    }

    /**
     * @return Entity[]
     */
    public function entities(): array
    {
        return $this->pcreParser->entities();
    }
}
