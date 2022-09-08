<?php
namespace TRegx\CleanRegex\Internal\Replace\Callback;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\GroupSubstitute;
use TRegx\CleanRegex\Replace\Callback\ReplaceCallbackArgumentStrategy;
use TRegx\SafeRegex\preg;

class ReplacePatternCallbackInvoker
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
    /** @var GroupSubstitute */
    private $substitute;

    public function __construct(Definition       $definition,
                                Subject          $subject,
                                int              $limit,
                                CountingStrategy $countingStrategy,
                                GroupSubstitute  $groupSubstitute)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->countingStrategy = $countingStrategy;
        $this->allFactory = new LazyMatchAllFactory(new ApiBase($definition, $subject));
        $this->substitute = $groupSubstitute;
    }

    public function invoke(callable $callback, ReplaceCallbackArgumentStrategy $strategy): string
    {
        $result = $this->pregReplaceCallback($callback, $replaced, $strategy);
        $this->countingStrategy->count($replaced, new LightweightGroupAware($this->definition));
        if ($replaced === 0) {
            return $this->substitute->substitute($result);
        }
        return $result;
    }

    private function pregReplaceCallback(callable $callback, ?int &$replaced, ReplaceCallbackArgumentStrategy $strategy): string
    {
        return preg::replace_callback($this->definition->pattern,
            $this->getObjectCallback($callback, $strategy),
            $this->subject,
            $this->limit,
            $replaced);
    }

    private function getObjectCallback(callable $callback, ReplaceCallbackArgumentStrategy $strategy): callable
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->allFactory, $strategy);
        return $object->getCallback();
    }
}
