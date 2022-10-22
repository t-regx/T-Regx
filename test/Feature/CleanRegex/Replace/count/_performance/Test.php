<?php
namespace Test\Feature\CleanRegex\Replace\count\_performance;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldCountAfterReplaceWith()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12, 13, 14');
        // when
        $replace->with('value');
        $count = $replace->count();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceWithReferences()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12, 13, 14');
        // when
        $replace->withReferences('value');
        $count = $replace->count();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceWithGroup()
    {
        // given
        $pattern = Pattern::of('(\d+)');
        $replace = $pattern->replace('12, 13, 14');
        // when
        $replace->withGroup(1);
        $count = $replace->count();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceCallback()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12, 13');
        // when
        $replace->callback(Functions::constant('value'));
        $count = $replace->count();
        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceExactlyWith()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12, 13, 14')->exactly(3);
        // when
        $replace->with('value');
        $count = $replace->count();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceExactlyWithReferences()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12, 13, 14')->exactly(3);
        // when
        $replace->withReferences('value');
        $count = $replace->count();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceExactlyWithGroup()
    {
        // given
        $pattern = Pattern::of('(\d+)');
        $replace = $pattern->replace('12, 13, 14')->exactly(3);
        // when
        $replace->withGroup(1);
        $count = $replace->count();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceExactlyCallback()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12, 13')->exactly(2);
        // when
        $replace->callback(Functions::constant('value'));
        $count = $replace->count();
        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldCountAfterReplaceExactlyCallbackThrew()
    {
        // given
        $pattern = Pattern::of('\d+');
        $replace = $pattern->replace('12')->exactly(2);
        // when
        try {
            $replace->callback(Functions::constant('value'));
        } catch (ReplacementExpectationFailedException $ignored) {
        }
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 2 replacement(s), but 1 replacement(s) were actually performed');
        // when
        $replace->count();
    }
}
