<?php
namespace TRegx\CleanRegex\Match;

use Throwable;

/**
 * @template T
 */
interface Optional
{
    /**
     * @return T
     */
    public function get();

    /**
     * @return T|never
     */
    public function orThrow(Throwable $throwable);

    /**
     * @template TorReturn
     * @param TorReturn $substitute
     * @return T|TorReturn
     */
    public function orReturn($substitute);

    /**
     * @template TorElse
     * @param (callable(): TorElse)|(\Closure(): TorElse) $substituteProducer
     * @return T|TorElse
     */
    public function orElse(callable $substituteProducer);

    /**
     * @template Tmap
     * @param (callable(T): Tmap)|(\Closure(): Tmap) $mapper
     * @return Optional<Tmap>
     */
    public function map(callable $mapper): Optional;
}
