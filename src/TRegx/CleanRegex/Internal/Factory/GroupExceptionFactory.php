<?php
namespace TRegx\CleanRegex\Internal\Factory;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupExceptionFactory
{
    /** @var Subjectable */
    private $subject;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Subjectable $subject, $nameOrIndex)
    {
        $this->subject = $subject;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function create(string $method)
    {
        return GroupNotMatchedException::forMethod($this->subject, $this->nameOrIndex, $method);
    }
}
