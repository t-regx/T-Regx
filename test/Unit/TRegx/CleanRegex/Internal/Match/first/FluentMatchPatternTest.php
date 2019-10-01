<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFirst()
    {
        // given
        $pattern = new FluentMatchPattern(['a' => 'foo', 'b' => 'bar', 6 => 'lorem', 7 => 'ipsum'], $this->mock());

        // when
        $result = $pattern->first();

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->mock());

        // then
        $this->expectException(NoFirstElementFluentException::class);

        // when
        $pattern->first();
    }

    private function mock(): NotMatchedWorker
    {
        /** @var NotMatchedWorker $mockObject */
        $mockObject = $this->createMock(NotMatchedWorker::class);
        return $mockObject;
    }
}
