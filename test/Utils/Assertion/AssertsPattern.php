<?php
namespace Test\Utils\Assertion;

use PHPUnit\Framework\Assert;
use Test\Utils\Assertion\Message\MatchMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Matcher;
use TRegx\CleanRegex\Pattern;

trait AssertsPattern
{
    private function assertPatternTests(Pattern $pattern, string $subject): void
    {
        $this->assertTrue($pattern->test($subject), "Failed to assert that $pattern matches '$subject'");
    }

    public function assertPatternIs(string $expected, Pattern $actual): void
    {
        Assert::assertSame($expected, $actual->delimited());
    }

    public function assertConsumesFirst(string $text, Pattern $pattern): void
    {
        $this->assertSame($pattern->match($text)->first()->text(), $text);
    }

    public function assertConsumesAll(string $text, array $texts, Pattern $pattern): void
    {
        $this->assertSame($pattern->search($text)->all(), $texts);
    }

    private function assertGroupMissing(Matcher $matcher, int $nameOrIndex): void
    {
        $message = new MatchMessage($matcher);
        $this->assertFalse($matcher->groupExists($nameOrIndex), $message->missingGroupMessage(GroupKey::of($nameOrIndex)));
    }
}
