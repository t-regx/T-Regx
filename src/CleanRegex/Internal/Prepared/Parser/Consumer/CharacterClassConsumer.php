<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\PosixClass;

class CharacterClassConsumer implements Consumer
{
    /** @var Feed */
    private $feed;
    /** @var PosixClass */
    private $posixClass;
    /** @var QuoteConsumer */
    private $quoteConsumer;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
        $this->posixClass = new PosixClass($feed);
        $this->quoteConsumer = new QuoteConsumer($feed);
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $entities->append(new ClassOpen());
        $this->consumeInitialBrackets($entities);
        $this->consumeCharacterClass($entities);
    }

    private function consumeInitialBrackets(EntitySequence $entities): void
    {
        if ($this->feed->startsWith(']')) {
            $this->consumeLiteral($entities, ']');
        } else if ($this->feed->startsWith('^]')) {
            $this->consumeLiteral($entities, '^]');
        }
    }

    private function consumeLiteral(EntitySequence $entities, string $literal): void
    {
        $this->feed->commit($literal);
        $entities->appendLiteral($literal);
    }

    private function consumeCharacterClass(EntitySequence $entities): void
    {
        while (!$this->feed->empty()) {
            $this->consumeLiteralCharacters($entities);
            if ($this->feed->startsWith(']')) {
                $this->consumeClassClose($entities);
                return;
            }
            $this->consumeSpecialCharacters($entities);
        }
    }

    private function consumeClassClose(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $entities->append(new ClassClose());
    }

    private function consumeLiteralCharacters(EntitySequence $entities): void
    {
        $literalAmount = $this->feed->stringLengthBeforeAny('\[]');
        if ($literalAmount > 0) {
            $this->consumeLiteral($entities, $this->feed->subString($literalAmount));
        }
    }

    private function consumeSpecialCharacters(EntitySequence $entities): void
    {
        if ($this->feed->startsWith('\Q')) {
            $this->quoteConsumer->consume($entities);
        } else if ($this->feed->startsWith('[')) {
            $this->consumeLiteral($entities, $this->posixClass->openedBracket());
        } else {
            $this->consumeEscapedCharacter($entities);
        }
    }

    private function consumeEscapedCharacter(EntitySequence $entities): void
    {
        if (!$this->feed->empty()) {
            $this->consumeFirst($entities);
            if (!$this->feed->empty()) {
                $this->consumeFirst($entities);
            }
        }
    }

    private function consumeFirst(EntitySequence $entities): void
    {
        $this->consumeLiteral($entities, $this->feed->firstLetter());
    }
}
