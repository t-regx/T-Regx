<?php
namespace Test\Utils\Assertion;

use PHPUnit\Framework\Assert;
use Test\Utils\Assertion\Message\MatchMessage;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Matcher;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

trait AssertsPattern
{
    private function assertPatternTests(Pattern $pattern, string $subject): void
    {
        $this->assertTrue($pattern->test($subject), "Failed to assert that $pattern matches '$subject'");
    }

    private function assertPatternFails(Pattern $pattern, string $subject): void
    {
        try {
            $this->assertTrue($pattern->fails($subject), "Failed to assert that $pattern fails to match '$subject'");
        } catch (MalformedPatternException $exception) {
            Assert::fail("Failed to execute a malformed pattern: $pattern");
        }
    }

    public function assertPatternIs(string $expected, Pattern $actual): void
    {
        Assert::assertSame($expected, $actual->delimited());
    }

    public function assertConsumesFirst(string $text, Pattern $pattern): void
    {
        try {
            $this->assertSame($text, $pattern->search($text)->first());
        } catch (MalformedPatternException $exception) {
            $pattern = str_replace("\r", "\n", $pattern);
            Assert::fail("Failed to execute a malformed pattern: $pattern");
        }
    }

    public function assertConsumesFirstSubstring(string $subject, string $expected, Pattern $pattern): void
    {
        try {
            $this->assertSame($expected, $pattern->search($subject)->first());
        } catch (MalformedPatternException $exception) {
            Assert::fail("Failed to execute a malformed pattern: $pattern");
        }
    }

    public function assertConsumesFirstGroup(string $subject, string $expectedGroup, Pattern $pattern): void
    {
        $this->assertSame($expectedGroup, $pattern->match($subject)->first()->group(1)->text());
    }

    public function assertConsumesAll(string $text, array $texts, Pattern $pattern): void
    {
        $this->assertSame($texts, $pattern->search($text)->all());
    }

    private function assertGroupMissing(Matcher $matcher, int $nameOrIndex): void
    {
        $message = new MatchMessage($matcher);
        $this->assertFalse($matcher->groupExists($nameOrIndex), $message->missingGroupMessage(GroupKey::of($nameOrIndex)));
    }
}
