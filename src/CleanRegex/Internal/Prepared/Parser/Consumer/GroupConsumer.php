<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupComment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupNull;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\Pcre;

class GroupConsumer implements Consumer
{
    public function condition(Feed $feed): Condition
    {
        return $feed->string('(');
    }

    public function consume(Feed $feed, EntitySequence $entities): void
    {
        $entities->append($this->consumeGroup($feed));
    }

    private function consumeGroup(Feed $feed): Entity
    {
        $namedGroup = $feed->matchedString("/^ ( \? (?:P?<|') [a-zA-Z0-9_\x80-\xFF@]* [>']? )/x", 1);
        if ($namedGroup->matched()) {
            [$groupNotation] = $namedGroup->consume();
            return new GroupOpen($groupNotation);
        }
        $groupDetails = $feed->matchedString($this->groupOpenParenthesisRegex(), 4);
        if (!$groupDetails->matched()) {
            return new GroupOpen();
        }
        [$type, $flags, $nonCapture, $comment] = $groupDetails->consume();
        if ($type === ':)') {
            return new GroupNull();
        }
        if ($type === ':') {
            return new GroupOpenFlags($flags ?? '');
        }
        if ($flags !== null) {
            if ($nonCapture === ':') {
                return new GroupOpenFlags($flags);
            }
            if ($nonCapture === ')') {
                return new GroupRemainder($flags);
            }
        }
        if ($comment !== null) {
            return new GroupComment($comment);
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    private function groupOpenParenthesisRegex(): string
    {
        if (Pcre::pcre2()) {
            $flags = '\^?[ismxnUJ]*(?:-[ismxnUJ]*)?';
        } else {
            $flags = '\^?[ismxXUJ-]*';
        }
        return "/^\?(?:(\:\)?)|($flags)([:)])|#([^)]*))/";
    }
}
