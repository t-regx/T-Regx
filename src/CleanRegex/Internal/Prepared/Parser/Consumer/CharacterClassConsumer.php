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
            $immediatelyFollowed->commit();
        } else {
            $immediatelyFollowed = $feed->string('^]');
            if ($immediatelyFollowed->consumable()) {
                $consumed .= '^]';
                $immediatelyFollowed->commit();
            }
        }
        $quoteConsumer = new QuoteConsumer();
        $posixClass = new PosixClassCondition($feed);
        while (true) {
            $closingTag = $feed->string(']');
            if ($closingTag->consumable()) {
                $closingTag->commit();
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                }
                $entities->append(new ClassClose());
                return;
            }
            $quote = $quoteConsumer->condition($feed);
            if ($quote->met($entities)) {
                $quote->commit();
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
            $feedLetter = $feed->letter();
            if (!$feedLetter->consumable()) {
                break;
            }
            $letter = $feedLetter->asString();
            $feedLetter->commit();
            if ($letter !== '\\') {
                $consumed .= $letter;
            } else {
                $escapedLetter = $feed->letter();
                if ($escapedLetter->consumable()) {
                    $escaped = $escapedLetter->asString();
                    $escapedLetter->commit();
                    $consumed .= "\\$escaped";
                } else {
                    $consumed .= "\\";
                }
            }
        }
        if ($consumed !== '') {
            $entities->appendLiteral($consumed);
        }
    }
}
