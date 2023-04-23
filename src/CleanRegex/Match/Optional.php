<?php
namespace TRegx\CleanRegex\Match;

use Throwable;

/**
 * @template T
 */
interface Optional
{
    /**
     * @phpstan-return T
     */
    public function get();

    /**
     * @phpstan-return T|never
     */
    public function orThrow(Throwable $throwable);

    /**
     * @template TorReturn
     * @phpstan-param TorReturn $substitute
     * @phpstan-return T|TorReturn
     */
    public function orReturn($substitute);

    /**
     * @template TorElse
     * @phpstan-param (callable(): TorElse)|(\Closure(): TorElse) $substituteProducer
     * @phpstan-return T|TorElse
     */
    public function orElse(callable $substituteProducer);

    /**
     * @template Tmap
     * @phpstan-param (callable(T): Tmap)|(\Closure(): Tmap) $mapper
     * @phpstan-return Optional<Tmap>
     */
    public function map(callable $mapper): Optional;
}
