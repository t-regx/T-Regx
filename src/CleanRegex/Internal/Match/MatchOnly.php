<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Match\Details\MatchDetail;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchPrime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class MatchOnly
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var Base */
    private $base;

    public function __construct(Definition $definition, Subject $subject, Base $base)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->base = $base;
    }

    public function get(Limit $limit): array
    {
        if ($limit->empty()) {
            $this->validatePattern();
            return [];
        }
        if ($limit->intValue() === 1) {
            return $this->getOneMatch();
        }
        return \array_slice($this->detailObjects(), 0, $limit->intValue());
    }

    private function validatePattern(): void
    {
        preg::match($this->definition->pattern, '');
    }

    private function getOneMatch(): array
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return [$this->detail($match)];
        }
        return [];
    }

    private function detailObjects(): array
    {
        $factory = new DetailObjectFactory($this->subject);
        return $factory->mapToDetailObjects($this->base->matchAllOffsets());
    }

    private function detail(RawMatchOffset $match): MatchDetail
    {
        $factory = new LazyMatchAllFactory($this->base);
        return DeprecatedMatchDetail::create($this->subject,
            0,
            new GroupPolyfillDecorator(new FalseNegative($match), $factory, 0),
            $factory,
            new MatchPrime($match));
    }
}
