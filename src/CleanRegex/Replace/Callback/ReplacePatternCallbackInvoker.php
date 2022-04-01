<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\CountingStrategy;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ReplacePatternCallbackInvoker
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var SubjectRs */
    private $substitute;
    /** @var CountingStrategy */
    private $countingStrategy;
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;
    private $allFactory;

    public function __construct(Definition       $definition,
                                Subject          $subject,
                                int              $limit,
                                SubjectRs        $substitute,
                                CountingStrategy $countingStrategy,
                                GroupAware       $groupAware,
                                GroupKey         $group)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
        $this->countingStrategy = $countingStrategy;
        $this->allFactory = new LazyMatchAllFactory(new ApiBase($definition, $subject));
        $this->groupAware = $groupAware;
        $this->group = $group;
    }

    public function invoke(callable $callback, ReplaceCallbackArgumentStrategy $strategy): string
    {
        $result = $this->pregReplaceCallback($callback, $replaced, $strategy);
        $this->countingStrategy->count($replaced, new LightweightGroupAware($this->definition));
        if ($replaced === 0) {
            if ($this->groupExists()) {
                return $this->substitute->substitute($this->subject) ?? $result;
            }
            throw new NonexistentGroupException($this->group);
        }
        return $result;
    }

    private function groupExists(): bool
    {
        if ($this->group->nameOrIndex() === 0) {
            return true;
        }
        return $this->groupAware->hasGroup($this->group);
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
        if ($this->limit === 0) {
            return static function () {
            };
        }
        return $this->createObjectCallback($callback, $strategy);
    }

    private function createObjectCallback(callable $callback, ReplaceCallbackArgumentStrategy $strategy): callable
    {
        $object = new ReplaceCallbackObject($callback, $this->subject, $this->allFactory, $this->limit, $strategy, $this->groupAware, $this->group);
        return $object->getCallback();
    }
}
