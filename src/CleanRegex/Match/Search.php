<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Limit;
use TRegx\CleanRegex\Internal\Match\Amount;
use TRegx\CleanRegex\Internal\Match\ArrayFunction;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupHandle;
use TRegx\CleanRegex\Internal\Match\Flat\DictionaryFunction;
use TRegx\CleanRegex\Internal\Match\Flat\ListFunction;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\SearchBase;
use TRegx\CleanRegex\Internal\Match\SearchItems;
use TRegx\CleanRegex\Internal\Match\SearchOnly;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Match\Stream\Base\TextStream;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Signatures\ArraySignatures;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class Search implements \Countable, \IteratorAggregate
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var SearchBase */
    private $searchBase;
    /** @var StreamBase */
    private $streamBase;
    /** @var SearchOnly */
    private $searchOnly;
    /** @var Amount */
    private $amount;
    /** @var SearchItems */
    private $searchItems;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->searchBase = new SearchBase($definition, $subject);
        $this->streamBase = new StreamBase(new ApiBase($definition, $subject));
        $this->searchOnly = new SearchOnly($this->searchBase);
        $this->amount = new Amount($this->searchBase);
        $this->searchItems = new SearchItems($this->searchBase);
    }

    public function test(): bool
    {
        return $this->amount->atLeastOne();
    }

    public function fails(): bool
    {
        return $this->amount->none();
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return $this->searchBase->matchAllTexts();
    }

    public function first(): string
    {
        $text = $this->searchBase->matchFirstOrNull();
        if ($text === null) {
            throw new SubjectNotMatchedException(new FirstMatchMessage(), $this->subject->asString());
        }
        return $text;
    }

    public function findFirst(): Optional
    {
        $text = $this->searchBase->matchFirstOrNull();
        if ($text === null) {
            return new EmptyOptional();
        }
        return new PresentOptional($text);
    }

    /**
     * @param int $limit
     * @return string[]
     */
    public function only(int $limit): array
    {
        return $this->searchOnly->get(new Limit($limit));
    }

    public function nth(int $index): string
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative nth: $index");
        }
        $texts = \array_values($this->searchBase->matchAllTexts());
        if (\array_key_exists($index, $texts)) {
            return $texts[$index];
        }
        throw NoSuchNthElementException::forSubject($index, \count($texts));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this as $text) {
            $consumer($text);
        }
    }

    public function map(callable $mapper): array
    {
        return $this->searchItems->mapped($mapper);
    }

    /**
     * @return string[]
     */
    public function filter(callable $predicate): array
    {
        return $this->searchItems->filter(new Predicate($predicate, 'filter'));
    }

    public function flatMap(callable $mapper): array
    {
        return $this->searchItems->flatMap(new ListFunction(new ArrayFunction($mapper, 'flatMap')));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        return $this->searchItems->flatMap(new DictionaryFunction(new ArrayFunction($mapper, 'flatMapAssoc')));
    }

    /**
     * @return string[]
     */
    public function distinct(): array
    {
        return \array_values(\array_unique($this->searchBase->matchAllTexts()));
    }

    public function count(): int
    {
        return $this->amount->intValue();
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->searchBase->matchAllTexts());
    }

    public function stream(): Stream
    {
        return new Stream(new TextStream($this->streamBase));
    }

    /**
     * @param string|int $nameOrIndex
     * @return string[][]
     */
    public function groupBy($nameOrIndex): array
    {
        return $this->performGroupBy(GroupKey::of($nameOrIndex));
    }

    private function performGroupBy(GroupKey $group): array
    {
        preg::match_all($this->definition->pattern, $this->subject->asString(), $matches, PREG_OFFSET_CAPTURE);
        if (!\array_key_exists($group->nameOrIndex(), $matches)) {
            throw new NonexistentGroupException($group);
        }
        $map = [];
        $handle = new GroupHandle(new ArraySignatures(\array_keys($matches)));
        foreach ($matches[$handle->groupHandle($group)] as $index => $match) {
            if ($match === '') {
                throw GroupNotMatchedException::forGroupBy($group);
            }
            [$value, $offset] = $match;
            if ($offset === -1) {
                throw GroupNotMatchedException::forGroupBy($group);
            }
            $map[$value][] = $matches[0][$index][0];
        }
        return $map;
    }

    /**
     * @param callable $groupMapper
     * @return string[][]
     */
    public function groupByCallback(callable $groupMapper): array
    {
        return $this->groupped(new GroupByFunction('groupByCallback', $groupMapper));
    }

    private function groupped(GroupByFunction $function): array
    {
        $result = [];
        foreach ($this as $text) {
            $result[$function->apply($text)][] = $text;
        }
        return $result;
    }

    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this as $text) {
            $accumulator = $reducer($accumulator, $text);
        }
        return $accumulator;
    }

    public function subject(): string
    {
        return $this->subject->asString();
    }
}
