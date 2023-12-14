<?php
namespace Test\Unit\_system;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Test\Fixture\WarningSnapshot;

class _matchWarningBefore extends TestCase
{
    private WarningSnapshot $warning;

    public function setUp(): void
    {
        $this->warning = new WarningSnapshot();
    }

    public function tearDown(): void
    {
        $this->warning->clear();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function first(): void
    {
        $pattern = new Pattern('test');
        $pattern->first('test');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function search(): void
    {
        $pattern = new Pattern('test');
        $pattern->search('test');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function match(): void
    {
        $pattern = new Pattern('test');
        $pattern->match('test');
    }
}
