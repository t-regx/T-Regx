<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\SplitPattern;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit_includingDelimiter()
    {
        // when
        $result = $this->split('([.])', '192..168...172..16');

        // then
        $empty = '';
        $this->assertEquals(['192', '.', $empty, '.', '168', '.', $empty, '.', $empty, '.', '172', '.', $empty, '.', '16'], $result);
    }

    /**
     * @test
     */
    public function shouldReturn_unchanged()
    {
        // when
        $matches = $this->split('(9)', 'Foo,Bar,Cat');

        // then
        $this->assertEquals(['Foo,Bar,Cat'], $matches);
    }

    private function split(string $pattern, string $subject): array
    {
        return (new SplitPattern(InternalPattern::standard($pattern), $subject))->split();
    }
}
