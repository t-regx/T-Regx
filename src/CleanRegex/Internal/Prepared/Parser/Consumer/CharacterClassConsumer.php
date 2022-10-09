<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Character;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;

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
        }
        $quoteConsumer = new QuoteConsumer();
        while (true) {
            $closingTag = $feed->string(']');
            if ($closingTag->consumable()) {
                $closingTag->commit();
                if ($consumed !== '') {
                    $entities->append(new Character($consumed));
                }
                $entities->append(new ClassClose());
                return;
            }
            $condition = $quoteConsumer->condition($feed);
            if ($condition->met($entities)) {
                $condition->commit();
                if ($consumed !== '') {
                    $entities->append(new Character($consumed));
                    $consumed = '';
                }
                $quoteConsumer->consume($feed, $entities);
                continue;
            }
            $condition = $feed->posixClass();
            if ($condition->consumable()) {
                $class = $condition->asString();
                $condition->commit();
                if ($consumed !== '') {
                    $entities->append(new Character($consumed));
                    $consumed = '';
                }
                $entities->append(new Character($class));
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
            $entities->append(new Character($consumed));
        }
    }
}
