<?php
namespace Test\Supposition\TRegx\lineEndings;

use PHPUnit\Framework\Assert;
use Test\Supposition\lineEndings\Ending;
use TRegx\CleanRegex\Pattern;

class LineEndingAssertion
{
    /** @var Pattern */
    private $pattern;
    /** @var AssertionMessage */
    private $message;

    public function __construct(Pattern $pattern, Ending $ending, string $convention)
    {
        $this->pattern = $pattern;
        $this->message = new AssertionMessage($ending, $convention, $pattern);
    }

    public function assertCommentClosed(string $subject): void
    {
        $this->assertConsumesClosed($subject);
        Assert::assertFalse($this->pattern->test(''), $this->message->applicableLeftOpen());
    }

    public function assertCommentIgnored(string $rejection): void
    {
        $this->assertConsumesIgnored();
        Assert::assertFalse($this->pattern->test($rejection), $this->message->inapplicableLeftOpen());
    }

    private function assertConsumesClosed(string $subject): void
    {
        $search = $this->pattern->search($subject);
        if ($search->fails()) {
            Assert::fail($this->message->matchClosed($subject));
        }
        Assert::assertSame($subject, $search->first(), $this->message->applicableClosed());
    }

    private function assertConsumesIgnored(): void
    {
        $search = $this->pattern->search('');
        if ($search->fails()) {
            Assert::fail($this->message->matchLeftOpen(''));
        }
        Assert::assertSame('', $search->first(), $this->message->inapplicableClosed());
    }
}
