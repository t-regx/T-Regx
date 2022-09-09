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
    private $limit;
    /** @var CountingStrategy */
    private $countingStrategy;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Definition $definition, Subject $subject, int $limit, CountingStrategy $countingStrategy)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->countingStrategy = $countingStrategy;
        $this->allFactory = new LazyMatchAllFactory(new ApiBase($definition, $subject));
    }

    public function invoke(callable $callback): string
    {
        $result = $this->pregReplaceCallback($callback, $replaced);
        $this->countingStrategy->applyReplaced($replaced);
        return $result;
    }

    private function pregReplaceCallback(callable $callback, ?int &$replaced): string
    {
        return preg::replace_callback($this->definition->pattern,
            $this->getObjectCallback($callback),
            $this->subject,
            $this->limit,
            $replaced);
    }

    private function getObjectCallback(callable $callback): callable
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->allFactory);
        return $object->getCallback();
    }
}
