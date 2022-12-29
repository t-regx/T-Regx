<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\PosixClassCondition;

class CharacterClassConsumer implements Consumer
{
    /** @var Feed */
    private $feed;
    /** @var PosixClassCondition */
    private $posixClass;
    /** @var QuoteConsumer */
    private $quoteConsumer;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
        $this->posixClass = new PosixClassCondition($feed);
        $this->quoteConsumer = new QuoteConsumer($feed);
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $entities->append(new ClassOpen());
        $consumed = '';
        if ($this->feed->startsWith(']')) {
            $consumed .= ']';
            $this->feed->commitSingle();
        } else {
            if ($this->feed->startsWith('^]')) {
                $consumed .= '^]';
                $this->feed->commit('^]');
            }
        }
        while (true) {
            if ($this->feed->startsWith(']')) {
                $this->feed->commitSingle();
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                }
                $entities->append(new ClassClose());
                return;
            }
            if ($this->feed->startsWith('\Q')) {
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                    $consumed = '';
                }
                $this->quoteConsumer->consume($entities);
                continue;
            }
            if ($this->posixClass->consumable()) {
                $class = $this->posixClass->asString();
                $this->posixClass->commit();
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                    $consumed = '';
                }
                $entities->appendLiteral($class);
                continue;
            }
            if ($this->feed->empty()) {
                break;
            }
            $letter = $this->feed->firstLetter();
            $this->feed->commitSingle();
            $consumed .= $letter;
            if ($letter === '\\') {
                if (!$this->feed->empty()) {
                    $consumed .= $this->feed->firstLetter();
                    $this->feed->commitSingle();
                }
            }
        }
        if ($consumed !== '') {
            $entities->appendLiteral($consumed);
        }
    }
}
