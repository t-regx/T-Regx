<?php
namespace Test\Unit\Detail;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\GroupException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class groupByteOffset extends TestCase
{
    /**
     * @test
     */
    public function byIndex(): void
    {
        $detail = $this->detail('Foo(Bar)', 'FooBar');
        $this->assertSame(3, $detail->groupByteOffset(1));
    }

    /**
     * @test
     */
    public function byName(): void
    {
        $detail = $this->detail('Foo(?<group>Bar)', 'FooBar');
        $this->assertSame(3, $detail->groupByteOffset('group'));
    }

    /**
     * @test
     */
    public function invalidName(): void
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupByteOffset('2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function missingGroups()
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupByteOffset('missing'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: 'missing'.");
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $detail = $this->detail('(?<foo>foo)?', 'bar');
        catching(fn() => $detail->groupByteOffset('foo'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group is not matched: 'foo'.");
    }

    private function detail(string $pattern, string $subject): Detail
    {
        return (new Pattern($pattern))->first($subject);
    }

    private function anyDetail(): Detail
    {
        return (new Pattern(''))->first('');
    }
}
