<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\ByteOffset;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DetailImpl;
use TRegx\CleanRegex\Match\Details\Group\BaseDetailGroup;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
use TRegx\CleanRegex\Match\Details\ReplaceMatchImpl;
use function call_user_func;
use function is_string;
use function mb_strlen;
use function strlen;
use function substr_replace;

class ReplaceCallbackObject
{
    /** @var callable */
    private $callback;
    /** @var Subjectable */
    private $subject;
    /** @var RawMatchesOffset */
    private $analyzedPattern;

    /** @var int */
    private $counter = 0;
    /** @var int */
    private $offsetModification = 0;
    /** @var string */
    private $subjectModification;
    /** @var int */
    private $limit;
    /** @var ReplaceCallbackArgumentStrategy */
    private $argumentStrategy;

    public function __construct(callable $callback,
                                Subjectable $subject,
                                RawMatchesOffset $analyzedPattern,
                                int $limit,
                                ReplaceCallbackArgumentStrategy $argumentStrategy)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->analyzedPattern = $analyzedPattern;
        $this->limit = $limit;
        $this->subjectModification = $this->subject->getSubject();
        $this->argumentStrategy = $argumentStrategy;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke($match);
        };
    }

    private function invoke(array $match): string
    {
        $result = call_user_func($this->callback, $this->matchObject());
        $replacement = $this->getReplacement($result);
        $this->modifySubject($replacement);
        $this->modifyOffset($match[0], $replacement);
        return $replacement;
    }

    private function matchObject()
    {
        return $this->argumentStrategy->mapArgument($this->createDetailObject());
    }

    private function createDetailObject(): ReplaceDetail
    {
        $index = $this->counter++;
        return new ReplaceMatchImpl(
            new DetailImpl(
                $this->subject,
                $index,
                $this->limit,
                new RawMatchesToMatchAdapter($this->analyzedPattern, $index),
                new EagerMatchAllFactory($this->analyzedPattern),
                new UserData(),
                new ReplaceMatchGroupFactoryStrategy($this->offsetModification)
            ),
            $this->offsetModification,
            $this->subjectModification
        );
    }

    private function getReplacement($replacement): string
    {
        if (is_string($replacement)) {
            return $replacement;
        }
        if ($replacement instanceof BaseDetailGroup) {
            return $this->groupAsReplacement($replacement);
        }
        if ($replacement instanceof Detail) {
            return $replacement;
        }
        throw new InvalidReplacementException($replacement);
    }

    private function groupAsReplacement(BaseDetailGroup $group): string
    {
        if ($group->matched()) {
            return $group->text();
        }
        throw GroupNotMatchedException::forReplacement($this->subject, $group->usedIdentifier());
    }

    private function modifyOffset(string $search, string $replacement): void
    {
        $this->offsetModification += mb_strlen($replacement) - mb_strlen($search);
    }

    private function modifySubject(string $replacement): void
    {
        [$text, $offset] = $this->analyzedPattern->getTextAndOffset($this->counter - 1);

        $this->subjectModification = substr_replace(
            $this->subjectModification,
            $replacement,
            $this->getReplaceStart($offset),
            strlen($text)
        );
    }

    private function getReplaceStart($offset): int
    {
        return ByteOffset::toCharacterOffset($this->subject->getSubject(), $offset) + $this->offsetModification;
    }
}
