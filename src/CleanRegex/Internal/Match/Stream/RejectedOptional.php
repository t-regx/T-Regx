<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use Throwable;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Match\Optional;

class RejectedOptional implements Optional
{
    use EmptyOptional;

    /** @var Throwable */
    private $throwable;

    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public function get()
    {
        throw $this->throwable;
    }

    public function orThrow(Throwable $throwable): void
    {
        throw $throwable;
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer();
    }
}
