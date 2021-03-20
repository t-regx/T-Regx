<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\flags;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = Pattern::prepare(['Foo', ['(Bar']], 'i');

        // when
        $flagIsAdded = $pattern->test('foo(bar');

        // then
        $this->assertTrue($flagIsAdded);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = Pattern::bind('Foo@bar', ['bar' => 'Bar'], 'i');

        // when
        $flagIsAdded = $pattern->test('foobar');

        // then
        $this->assertTrue($flagIsAdded);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::inject('Foo@', ['Bar'], 'i');

        // when
        $flagIsAdded = $pattern->test('foobar');

        // then
        $this->assertTrue($flagIsAdded);
    }
}
