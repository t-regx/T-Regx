<?php
namespace TRegx\CleanRegex\Internal\Replace\Callback;

use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactoryMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchAllFactoryPrime;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Detail;

class ReplaceCallbackObject
{
    /** @var ReplaceFunction */
    private $function;
    /** @var Subject */
    private $subject;
    /** @var MatchAllFactory */
    private $factory;
    /** @var int */
    private $counter = 0;

    public function __construct(ReplaceFunction $function, Subject $subject, MatchAllFactory $factory)
    {
        $this->function = $function;
        $this->subject = $subject;
        $this->factory = $factory;
    }

    public function getCallback(): callable
    {
        return function (array $match) {
            return $this->function->apply($this->createDetailObject());
        };
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
}
