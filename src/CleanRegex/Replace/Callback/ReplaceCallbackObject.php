<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Replace\Details\Modification;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\CapturingGroup;
use TRegx\CleanRegex\Replace\Details\ReplaceDetail;

class ReplaceCallbackObject
{
    /** @var callable */
    private $callback;
    /** @var Subject */
    private $subject;
    /** @var MatchAllFactory */
    private $factory;
    /** @var int */
    private $counter = 0;
    /** @var int */
    private $byteOffsetModification = 0;
    /** @var string */
    private $subjectModification;
    /** @var int */
    private $limit;
    /** @var ReplaceCallbackArgumentStrategy */
    private $argumentStrategy;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $groupKey;

    public function __construct(callable                        $callback,
                                Subject                         $subject,
                                MatchAllFactory                 $factory,
                                int                             $limit,
                                ReplaceCallbackArgumentStrategy $argumentStrategy,
                                GroupAware                      $groupAware,
                                GroupKey                        $groupKey)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->factory = $factory;
        $this->subjectModification = $this->subject->asString();
        $this->limit = $limit;
        $this->argumentStrategy = $argumentStrategy;
        $this->groupAware = $groupAware;
        $this->groupKey = $groupKey;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke($match);
        };
    }

    private function invoke(array $match): string
    {
        if (!$this->groupExists()) {
            throw new NonexistentGroupException($this->groupKey);
        }
        $result = ($this->callback)($this->matchObject());
        $replacement = $this->getReplacement($result);
        $this->modifySubject($replacement);
        $this->modifyOffset($match[0], $replacement);
        return $replacement;
    }

    private function groupExists(): bool
    {
        if ($this->groupKey->nameOrIndex() === 0) {
            return true;
        }
        return $this->groupAware->hasGroup($this->groupKey);
    }

    private function matchObject()
    {
        return $this->argumentStrategy->mapArgument($this->createDetailObject());
    }

    private function createDetailObject(): ReplaceDetail
    {
        $index = $this->counter++;
        $match = new RawMatchesToMatchAdapter($this->factory->getRawMatches(), $index);
        return new ReplaceDetail(DeprecatedMatchDetail::create(
            $this->subject,
            $index,
            $this->limit,
            $match,
            $this->factory,
            new UserData(),
            new ReplaceMatchGroupFactoryStrategy(
                $this->byteOffsetModification,
                $this->subjectModification)),
            new Modification($match, $this->subjectModification, $this->byteOffsetModification));
    }

    private function getReplacement($replacement): string
    {
        if (\is_string($replacement)) {
            return $replacement;
        }
        if ($replacement instanceof CapturingGroup) {
            return $this->groupAsReplacement($replacement);
        }
        if ($replacement instanceof Detail) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }

    private function groupAsReplacement(CapturingGroup $group): string
    {
        if ($group->matched()) {
            return $group->text();
        }
        throw GroupNotMatchedException::forReplacement(GroupKey::of($group->usedIdentifier()));
    }

    private function modifyOffset(string $search, string $replacement): void
    {
        $this->byteOffsetModification += \strlen($replacement) - \strlen($search);
    }

    private function modifySubject(string $replacement): void
    {
        [$text, $offset] = $this->factory->getRawMatches()->getTextAndOffset($this->counter - 1);

        $this->subjectModification = \substr_replace(
            $this->subjectModification,
            $replacement,
            $offset + $this->byteOffsetModification,
            \strlen($text));
    }
}
