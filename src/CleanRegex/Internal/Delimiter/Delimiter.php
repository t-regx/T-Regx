<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class Delimiter
{
    /** @var array */
    private static $validDelimiters = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1"];
    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function delimited(Quotable $quotable, Flags $flags): string
    {
        if ($this->legal()) {
            return $this->delimiter . $quotable->quote($this->delimiter) . $this->delimiter . $flags;
        }
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    private function legal(): bool
    {
        return \in_array($this->delimiter, self::$validDelimiters, true);
    }

    public static function suitable(string $delimiterable): Delimiter
    {
        foreach (Delimiters::getDelimiters() as $delimiter) {
            if (\strpos($delimiterable, $delimiter) === false) {
                return new Delimiter($delimiter);
            }
        }
        throw new ExplicitDelimiterRequiredException($delimiterable);
    }
}
