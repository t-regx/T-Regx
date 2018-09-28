<?php
namespace TRegx\CleanRegex\Internal\Factory;

use TRegx\CleanRegex\Exception\CleanRegex\GroupNotMatchedException;

class GroupExceptionFactory
{
    /** @var string */
    private $subject;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(string $subject, $nameOrIndex)
    {
        $this->subject = $subject;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function create(string $method)
    {
        return GroupNotMatchedException::forMethod($this->subject, $this->nameOrIndex, $method);
    }
}
