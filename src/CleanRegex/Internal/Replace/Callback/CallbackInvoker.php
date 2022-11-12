<?php
namespace TRegx\CleanRegex\Internal\Replace\Callback;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

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

    public function __construct(Definition $definition, Subject $subject, int $pregLimit, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->pregLimit = $pregLimit;
        $this->countingStrategy = $countingStrategy;
        $this->allFactory = new LazyMatchAllFactory(new ApiBase($definition, $subject));
    }

    public function invoke(callable $callback): string
    {
        return $this->invokeFunction(new ReplaceFunction($callback));
    }

    private function invokeFunction(ReplaceFunction $function): string
    {
        $result = $this->pregReplaceCallback($function, $replaced);
        $this->countingStrategy->applyReplaced($replaced);
        return $result;
    }

    private function pregReplaceCallback(ReplaceFunction $function, ?int &$replaced): string
    {
        return preg::replace_callback($this->definition->pattern,
            $this->getObjectCallback($function),
            $this->subject,
            $this->pregLimit,
            $replaced);
    }

    private function getObjectCallback(ReplaceFunction $function): callable
    {
        $object = new ReplaceCallbackObject($function, $this->subject, $this->allFactory);
        return $object->getCallback();
    }
}
