<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\PosixClassCondition;

class CharacterClassConsumer implements Consumer
{
    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $feed->commitSingle();
        $entities->append(new ClassOpen());
        $consumed = '';
        if ($feed->startsWith(']')) {
            $consumed .= ']';
            $feed->commitSingle();
        } else {
            if ($feed->startsWith('^]')) {
                $consumed .= '^]';
                $feed->commit('^]');
            }
        }
        $quoteConsumer = new QuoteConsumer();
        $posixClass = new PosixClassCondition($feed);
        while (true) {
            if ($feed->startsWith(']')) {
                $feed->commitSingle();
                if ($consumed !== '') {
                    $entities->appendLiteral($consumed);
                }
                $entities->append(new ClassClose());
                return;
            }
            if ($feed->startsWith('\Q')) {
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
            $consumed .= $letter;
            if ($letter === '\\') {
                if (!$feed->empty()) {
                    $consumed .= $feed->firstLetter();
                    $feed->commitSingle();
                }
            }
        }
        if ($consumed !== '') {
            $entities->appendLiteral($consumed);
        }
    }
}
