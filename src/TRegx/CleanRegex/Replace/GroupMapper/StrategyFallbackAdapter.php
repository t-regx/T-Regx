<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;

class StrategyFallbackAdapter implements GroupMapper
{
    /** @var GroupMapper */
    private $mapper;
    /** @var ReplaceSubstitute */
    private $substitute;
    /** @var string */
    private $subject;

    public function __construct(GroupMapper $mapper, ReplaceSubstitute $substitute, string $subject)
    {
        $this->mapper = $mapper;
        $this->substitute = $substitute;
        $this->subject = $subject;
    }

    public function map(string $occurrence): ?string
    {
        $result = $this->mapper->map($occurrence);
        if ($result === null) {
            return $this->substitute->substitute($this->subject);
        }
        return $result;
    }
}
