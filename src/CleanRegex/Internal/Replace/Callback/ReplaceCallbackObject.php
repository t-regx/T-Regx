<?php
namespace TRegx\CleanRegex\Internal\Replace\Callback;

use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactoryMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchAllFactoryPrime;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Detail;

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

    public function __construct(callable $callback, Subject $subject, MatchAllFactory $factory)
    {
        $this->callback = $callback;
        $this->subject = $subject;
        $this->factory = $factory;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->invoke();
        };
    }

    private function invoke(): string
    {
        $result = ($this->callback)($this->createDetailObject());
        return $this->getReplacement($result);
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
        throw new InvalidReplacementException(new ValueType($replacement));
    }
}
