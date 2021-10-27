<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Match\Optional;

class RejectedOptional implements Optional
{
    use EmptyOptional;

    /** @var Rejection */
    private $rejection;

    public function __construct(Rejection $rejection)
    {
        $this->rejection = $rejection;
    }

    public function orThrow(string $exceptionClassName = null): void
    {
        $this->rejection->throw($exceptionClassName);
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer();
    }
}
