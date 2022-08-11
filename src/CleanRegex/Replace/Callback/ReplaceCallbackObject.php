<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchesFirstPrime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Match\Group;
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
    private $limit;
    /** @var ReplaceCallbackArgumentStrategy */
    private $argumentStrategy;

    public function __construct(callable                        $callback,
                                Subject                         $subject,
                                MatchAllFactory                 $factory,
                                int                             $limit,
                                ReplaceCallbackArgumentStrategy $argumentStrategy)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->factory = $factory;
        $this->limit = $limit;
        $this->argumentStrategy = $argumentStrategy;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke();
        };
    }

    private function invoke(): string
    {
        $result = ($this->callback)($this->matchObject());
        return $this->getReplacement($result);
    }

    private function matchObject()
    {
        return $this->argumentStrategy->mapArgument($this->createDetailObject());
    }

    private function createDetailObject(): ReplaceDetail
    {
        $index = $this->counter++;
        $matches = $this->factory->getRawMatches();
        return new ReplaceDetail(DeprecatedMatchDetail::create(
            $this->subject,
            $index,
            new RawMatchesToMatchAdapter($matches, $index),
            $this->factory,
            new MatchesFirstPrime($matches)), $this->limit);
    }

    private function getReplacement($replacement): string
    {
        if (\is_string($replacement)) {
            return $replacement;
        }
        if ($replacement instanceof Group) {
            return $this->groupAsReplacement($replacement);
        }
        if ($replacement instanceof Detail) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }

    private function groupAsReplacement(Group $group): string
    {
        if ($group->matched()) {
            return $group->text();
        }
        throw GroupNotMatchedException::forReplacement(GroupKey::of($group->usedIdentifier()));
    }
}
