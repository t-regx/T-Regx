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
        $groupDetails = $feed->matchedString($this->groupOpenRegex(), 5);
        if (!$groupDetails->matched()) {
            return new GroupOpen('');
        }
        [$groupNotation, $type, $options, $optionsMode, $comment] = $groupDetails->consume();
        if ($groupNotation !== null) {
            return new GroupOpen('?' . $groupNotation);
        }
        if ($type === ':)') {
            return new GroupNull();
        }
        if ($type === ':') {
            return new GroupOpenFlags($options ?? '');
        }
        if ($optionsMode === ':') {
            return new GroupOpenFlags($options);
        }
        if ($optionsMode === ')') {
            return new GroupRemainder($options);
        }
        if ($comment !== null) {
            return new GroupComment($comment);
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    private function groupOpenRegex(): string
    {
        if (Pcre::pcre2()) {
            $flags = '\^?[ismxnUJ]*(?:-[ismxnUJ]*)?';
        } else {
            $flags = '\^?[ismxXUJ-]*';
        }
        /** @lang RegExp */
        return "/^
            \?
             (?:
               ( (?:<|P<|') [a-zA-Z0-9_\x80-\xFF@]* [>']?)
              |
               (:\)?)
              |
               ($flags)([:)])
              |
               \#([^)]*)
             )
           /x";
    }
}
