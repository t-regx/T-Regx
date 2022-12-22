<?php
namespace TRegx\CleanRegex\Internal\Replace\Callback;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactoryMatchOffset;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchAllFactoryPrime;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Detail;
use TRegx\SafeRegex\preg;

/**
 * @deprecated
 */
class CallbackInvoker
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $pregLimit;
    /** @var CountingStrategy */
    private $countingStrategy;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var MatchAllFactoryPrime */
    private $primeFactory;

    public function __construct(Definition $definition, Subject $subject, int $pregLimit, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->pregLimit = $pregLimit;
        $this->countingStrategy = $countingStrategy;
        $this->allFactory = new LazyMatchAllFactory(new ApiBase($definition, $subject));
        $this->primeFactory = new MatchAllFactoryPrime($this->allFactory);
    }

    public function invoke(callable $callback): string
    {
        return $this->invokeFunction(new ReplaceFunction($callback));
    }

    private function invokeFunction(ReplaceFunction $function): string
    {
        $index = 0;
        $replaced = preg::replace_callback($this->definition->pattern,
            function () use ($function, &$index) {
                return $function->apply($this->detail($index++));
            },
            $this->subject,
            $this->pregLimit,
            $replacedAmount);
        $this->countingStrategy->applyReplaced($replacedAmount);
        return $replaced;
    }

    public function detail(int $index): Detail
    {
        return DeprecatedMatchDetail::create(
            $this->subject,
            $index,
            new MatchAllFactoryMatchOffset($this->allFactory, $index),
            $this->allFactory,
            $this->primeFactory);
    }
}
