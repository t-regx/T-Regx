<?php
namespace Test\Utils\Assertion;

use PHPUnit\Framework\Assert;
use TRegx\CleanRegex\Match\Details\Detail;

trait AssertsDetail
{
    public function assertDetailText(string $expected, Detail $actual)
    {
        Assert::assertSame($expected, $actual->text());
    }

    public function assertDetailOffset(int $expected, Detail $actual)
    {
        Assert::assertSame($expected, $actual->offset());
    }

    public function assertDetailIndex(int $expectedIndex, Detail $first)
    {
        Assert::assertSame($expectedIndex, $first->index());
    }

    public function assertDetailsIndexed(?Detail ...$details): void
    {
        foreach ($details as $index => $detail) {
            if ($detail === null) {
                continue;
            }
            Assert::assertSame($index, $detail->index());
        }
    }

    public function assertDetailSubject(string $subject, Detail $detail): void
    {
        $this->assertDetailsSubject($subject, $detail);
    }

    public function assertDetailsSubject(string $subject, Detail ...$details): void
    {
        foreach ($details as $detail) {
            Assert::assertSame($subject, $detail->subject());
        }
    }

    public function assertDetailAll(array $expected, Detail $detail): void
    {
        $this->assertDetailsAll($expected, $detail);
    }

    public function assertDetailsAll(array $expected, Detail ...$details): void
    {
        foreach ($details as $detail) {
            Assert::assertSame($expected, $detail->all());
        }
    }
}
