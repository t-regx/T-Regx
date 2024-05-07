<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\GroupException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class groupOffset extends TestCase
{
    public function test(): void
    {
        $detail = $this->detail('(Fury)', 'Óurs is the Fury');
        $this->assertSame(12, $detail->groupOffset(1));
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $detail = $this->detail('(?<foo>foo)?', 'bar');
        catching(fn() => $detail->groupOffset('foo'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group is not matched: 'foo'.");
    }

    private function detail(string $pattern, string $subject): Detail
    {
        return (new Pattern($pattern))->first($subject);
    }
}
