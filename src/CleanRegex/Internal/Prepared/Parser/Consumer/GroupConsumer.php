<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Consumer;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\AutoCapture\Group\GroupAutoCapture;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupComment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupNull;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenConditional;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\EntitySequence;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\MatchedString;
use TRegx\Pcre;

class GroupConsumer implements Consumer
{
    /** @var Feed */
    private $feed;
    /** @var GroupAutoCapture */
    private $autoCapture;
    /** @var string */
    private $openGroupRegex;

    public function __construct(Feed $feed, GroupAutoCapture $autoCapture)
    {
        $this->feed = $feed;
        $this->autoCapture = $autoCapture;
        $this->openGroupRegex = $this->groupOpenRegex();
    }

    public function consume(EntitySequence $entities): void
    {
        $this->feed->commitSingle();
        $entities->append($this->consumeGroup($entities));
    }

    private function consumeGroup(EntitySequence $entities): Entity
    {
        $groupDetails = new MatchedString($this->feed, $this->openGroupRegex, 6);
        if (!$groupDetails->matched()) {
            if ($this->imposedNonCapture($entities)) {
                return new GroupOpen('?:');
            }
            return new GroupOpen('');
        }
        [$groupNotation, $type, $options, $optionsMode, $comment, $reference] = $groupDetails->consume();
        if ($groupNotation !== null) {
            return new GroupOpen('?' . $groupNotation);
        }
        if ($type === ':)') {
            return new GroupNull();
        }
        if ($type === ':') {
            return new GroupOpenFlags('', new IdentityOptionSetting(''));
        }
        if ($type === '(') {
            return new GroupOpenConditional();
        }
        if ($type === '>' || $type === '=' || $type === '!') {
            return new GroupOpen('?' . $type);
        }
        if ($reference !== null) {
            return new GroupOpen($reference);
        }
        if ($optionsMode === ':') {
            return new GroupOpenFlags($options, $this->autoCapture->optionSetting($options));
        }
        if ($optionsMode === ')') {
            return new GroupRemainder($options, $this->autoCapture->optionSetting($options));
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
        $namedGroup = "(?:<|P<|') [a-zA-Z0-9_\x80-\xFF@]* [>']?";
        if (Pcre::pcre2()) {
            $flags = '\^?[ismxnUJ]*(?:-[ismxnUJ]*)?';
            /** @lang RegExp */
            return "/^
            \?
             (?:
               ($namedGroup)
              |
               (:\)?)
              |
               ($flags)([:)])
              |
               \#([^)]*)
             )
           /x";
        }
        $flags = '\^?[ismnxXUJ-]*';
        /** @lang RegExp */
        return "/^
             (?:
               \?($namedGroup)
              |
               \?( [!>=(]|:\)? )
              |
               \?($flags)([:)])
              |
               \?\#([^)]*)
              |
               ([?*])
           )/x";
    }

    private function imposedNonCapture(EntitySequence $entities): bool
    {
        return $entities->flags()->noAutoCapture() && $this->autoCapture->imposedNonCapture();
    }
}
