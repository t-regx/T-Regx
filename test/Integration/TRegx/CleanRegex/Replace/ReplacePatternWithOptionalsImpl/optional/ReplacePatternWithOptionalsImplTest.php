<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\ReplacePatternWithOptionalsImplTest\optional;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NotReplacedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\ReplacePattern;
use TRegx\CleanRegex\Replace\ReplacePatternWithOptionalsImpl;

class ReplacePatternWithOptionalsImplTest extends TestCase
{
    /*
     * @test
     */
    public function replaced_shouldDelegate()
    {
        // given
        $underTest = new ReplacePatternWithOptionalsImpl($this->mock('delegated'), new InternalPattern(''), '', 0);

        // when
        $result1 = $underTest->orThrow()->with('');
        $result2 = $underTest->orReturn('')->with('');
        $result3 = $underTest->orElse(function () {
        })->with('');

        // then
        $this->assertEquals('delegated', $result1);
        $this->assertEquals('delegated', $result2);
        $this->assertEquals('delegated', $result3);
    }

    /**
     * @test
     */
    public function notReplaced_orThrow()
    {
        // given
        $underTest = new ReplacePatternWithOptionalsImpl($this->mock(), new InternalPattern(''), '', 0);

        // then
        $this->expectException(NotReplacedException::class);

        // when
        $underTest->orThrow()->with('');
    }

    /**
     * @test
     */
    public function notReplaced_orReturn()
    {
        // given
        $underTest = new ReplacePatternWithOptionalsImpl($this->mock(), new InternalPattern(''), '', 0);

        // when
        $result = $underTest->orReturn('Custom message')->with('');

        // then
        $this->assertEquals('Custom message', $result);
    }

    /**
     * @test
     */
    public function notReplaced_orElse()
    {
        // given
        $underTest = new ReplacePatternWithOptionalsImpl($this->mock(), new InternalPattern(''), 'Apple', 0);

        // when
        $result = $underTest
            ->orElse(function (string $subject) {
                return "Subject: '$subject'";
            })
            ->with('');

        // then
        $this->assertEquals("Subject: 'Apple'", $result);
    }

    public function mock(string $result = null): ReplacePattern
    {
        /** @var ReplacePattern $delegate */
        $delegate = $this->createMock(ReplacePattern::class);
        $delegate->method('with')->willReturn($result ?? '');
        return $delegate;
    }
}
