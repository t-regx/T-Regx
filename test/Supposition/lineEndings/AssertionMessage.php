<?php
namespace Test\Supposition\TRegx\lineEndings;

use Test\Supposition\lineEndings\Ending;
use TRegx\CleanRegex\Pattern;

class AssertionMessage
{
    /** @var Ending */
    private $ending;
    /** @var string */
    private $convention;
    /** @var Pattern */
    private $pattern;

    public function __construct(Ending $ending, string $convention, Pattern $pattern)
    {
        $this->ending = $ending;
        $this->convention = $convention;
        $this->pattern = $pattern;
    }

    public function applicableClosed(): string
    {
        return format("Failed to assert that applicable '{}' ({}) closed comment in '{}'",
            [$this->ending, $this->ending->name(), $this->convention]);
    }

    public function applicableLeftOpen(): string
    {
        return format("Failed to assert that applicable '{}' ({}) did not leave comment open in '{}'",
            [$this->ending, $this->ending->name(), $this->convention]);
    }

    public function inapplicableClosed(): string
    {
        return format("Failed to assert that inapplicable '{}' closed comment with '{}' in {}",
            [$this->ending, $this->convention, $this->pattern]);
    }

    public function inapplicableLeftOpen(): string
    {
        return format("Failed to assert that inapplicable '{}' did not leave comment open with '{}' in {}",
            [$this->ending, $this->convention, $this->pattern]);
    }

    public function matchClosed(string $subject): string
    {
        return format("Failed to assert that {} matched '{}', indicating that comment wasn't closed.",
            [$this->pattern, $subject]);
    }

    public function matchLeftOpen(string $subject): string
    {
        return format("Failed to assert that {} matched '{}', indicating that '{}' did in fact close the comment.",
            [$this->pattern, $subject, $this->ending]);
    }
}
