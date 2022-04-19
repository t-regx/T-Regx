<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Details\Group\ReplaceMatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchesToMatchAdapter;
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
    private $limit;
    /** @var ReplaceCallbackArgumentStrategy */
    private $argumentStrategy;
    /** @var SubjectAlteration */
    private $alteration;

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
        $this->alteration = new SubjectAlteration($subject);
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke($match);
        };
    }

    private function invoke(array $match): string
    {
        $result = ($this->callback)($this->matchObject());
        $replacement = $this->getReplacement($result);
        $this->modify($match, $replacement);
        return $replacement;
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
            $match,
            $this->factory,
            new ReplaceMatchGroupFactoryStrategy(
                $this->alteration->byteOffset(),
                $this->alteration->subject())), $this->limit,
            $this->alteration->modification($match->byteOffset()));
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

    private function modify(array $match, string $replacement): void
    {
        [$text, $offset] = $this->textAndOffset($match);
        $this->alteration->modify($text, $offset, $replacement);
    }

    private function textAndOffset(array $match): array
    {
        return [$match[0], $this->matchOffset()];
    }

    private function matchOffset(): int
    {
        [$_, $offset] = $this->factory->getRawMatches()->getTextAndOffset($this->counter - 1);
        return $offset;
    }
}
