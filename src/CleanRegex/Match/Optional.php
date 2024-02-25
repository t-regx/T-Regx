<?php
namespace TRegx\CleanRegex\Match;

use Throwable;

/**
 * @deprecated
 */
interface Optional
{
    /**
     * @deprecated
     */
    public function get();

    /**
     * @deprecated
     */
    public function orThrow(Throwable $throwable);

    /**
     * @deprecated
     */
    public function orReturn($substitute);

    /**
     * @deprecated
     */
    public function orElse(callable $substituteProducer);

    /**
     * @deprecated
     */
    public function map(callable $mapper): Optional;
}
