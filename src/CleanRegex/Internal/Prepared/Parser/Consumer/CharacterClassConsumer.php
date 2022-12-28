<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\PosixClassCondition;

class CharacterClassConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('[');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append(new ClassOpen());
        $consumed = '';
        $immediatelyFollowed = $feed->string(']');
        if ($immediatelyFollowed->consumable()) {
            $consumed .= ']';
            $feed->commitSingle();
        } else {
            $immediatelyFollowed = $feed->string('^]');
            if ($immediatelyFollowed->consumable()) {
                $consumed .= '^]';
                $feed->commit('^]');
            }
        }
        $quoteConsumer = new QuoteConsumer();
        $posixClass = new PosixClassCondition($feed);
        while (true) {
            $closingTag = $feed->string(']');
            if ($closingTag->consumable()) {
                $feed->commitSingle();
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                }
                $entities->append(new ClassClose());
                return;
            }
            $quote = $quoteConsumer->condition($feed);
            if ($quote->met($entities)) {
                $feed->commit('\Q');
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                    $consumed = '';
                }
                $quoteConsumer->consume($feed, $entities);
                continue;
            }
            if ($posixClass->consumable()) {
                $class = $posixClass->asString();
                $posixClass->commit();
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                    $consumed = '';
                }
                $entities->appendLiteral($class);
                continue;
            }
            if ($feed->empty()) {
                break;
            }
            $letter = $feed->firstLetter();
            $feed->commitSingle();
            if ($letter !== '\\') {
                $consumed .= $letter;
            } else {
                if ($feed->empty()) {
                    $consumed .= "\\";
                } else {
                    $escaped = $feed->firstLetter();
                    $feed->commitSingle();
                    $consumed .= "\\$escaped";
                }
            }
        }
        if ($consumed !== '') {
            $entities->appendLiteral($consumed);
        }
    }
}
