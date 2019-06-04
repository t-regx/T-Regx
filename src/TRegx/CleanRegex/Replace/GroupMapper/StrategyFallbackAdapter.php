<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;

class StrategyFallbackAdapter implements GroupMapper
{
    /** @var GroupMapper */
    private $mapper;
    /** @var NonReplacedStrategy */
    private $nonReplacedStrategy;
    /** @var string */
    private $subject;

    public function __construct(GroupMapper $mapper, NonReplacedStrategy $nonReplacedStrategy, string $subject)
    {
        $this->mapper = $mapper;
        $this->nonReplacedStrategy = $nonReplacedStrategy;
        $this->subject = $subject;
    }

    public function map(string $occurrence): ?string
    {
        $result = $this->mapper->map($occurrence);
        if ($result === null) {
            return $this->nonReplacedStrategy->replacementResult($this->subject);
        }
        return $result;
    }
}
