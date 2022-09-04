<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactoryMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchAllFactoryPrime;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Match\Group;

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
    /** @var ReplaceCallbackArgumentStrategy */
    private $argumentStrategy;

    public function __construct(callable                        $callback,
                                Subject                         $subject,
                                MatchAllFactory                 $factory,
                                ReplaceCallbackArgumentStrategy $argumentStrategy)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->factory = $factory;
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

    private function createDetailObject(): Detail
    {
        $index = $this->counter++;
        return DeprecatedMatchDetail::create(
            $this->subject,
            $index,
            new MatchAllFactoryMatchOffset($this->factory, $index),
            $this->factory,
            new MatchAllFactoryPrime($this->factory));
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
