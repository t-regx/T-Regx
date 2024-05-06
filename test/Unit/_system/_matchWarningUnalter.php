<?php
namespace Test\Unit\_system;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Test\Fixture\WarningSnapshot;
use function Test\Fixture\Functions\catching;

class _matchWarningUnalter extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function pattern()
    {
        $this->pattern = new Pattern('(?=word\K)');
    }

    /**
     * @test
     */
    public function first()
    {
        $warning = new WarningSnapshot();
        catching(fn() => $this->pattern->first('word'));
        $warning->assertEquals();
    }

    /**
     * @test
     */
    public function search()
    {
        $warning = new WarningSnapshot();
        catching(fn() => $this->pattern->search('word'));
        $warning->assertEquals();
    }

    /**
     * @test
     */
    public function match()
    {
        $warning = new WarningSnapshot();
        catching(fn() => $this->pattern->match('word'));
        $warning->assertEquals();
    }
}
