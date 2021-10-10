<?php
namespace TRegx\CleanRegex\Match;

use Throwable;

interface Optional
{
    /**
     * @deprecated Currently, {@see Optional::orThrow} takes
     * the optional classname of the exception, and tries to
     * instantiate it. In the next release, it will take an
     * optional instance of {@see Throwable}. To dynamically
     * create an exception, use {@see Optional::orElse}.
     *
     * This is done for two reasons: first, the instantiation
     * of exceptions suggests an implicit signature: message
     * and the subject, both of which are optional. Implicit
     * isn't the best design, explicit exception creation
     * would be better. Hence you create your own exception
     * instance and pass it to {@see Optional::orThrow} or
     * throw it in {@see Optional::orElse}.
     * The second reason is the stack trace. With the current
     * implementation, it's impossible to preserve a stack
     * trace that could actually help the user, because the
     * real call is burried in the stack trace of T-Regx
     * internals. With exception passed to {@see Optional::orThrow}
     * or thrown in {@see Optional::orElse} it will be much
     * more helpful.
     */
    public function orThrow(string $exceptionClassName = null);

    public function orReturn($substitute);

    public function orElse(callable $substituteProducer);

    public function map(callable $mapper): Optional;
}
