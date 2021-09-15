<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

/**
 * I would make it an interface, but for some reason
 * PHP doesn't allow access to protected methods between
 * instances of the same interface. But does allow
 * for abstract classes ¯\_(ツ)_/¯
 */
abstract class Phrase
{
    public abstract function conjugated(string $delimiter): string;

    protected abstract function unconjugated(string $delimiter): string;
}
