<?php
namespace Test\Utils\Assertion;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Pattern;

trait AssertsPattern
{

    private function assertPatternTests(Pattern $pattern, string $subject): void
    {
        $this->assertTrue($pattern->test($subject), "Failed to assert that $pattern matches '$subject'");
    }

    public function assertSamePattern(string $expected, Pattern $actual): void
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
}
