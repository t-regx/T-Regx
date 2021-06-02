<?php
namespace Test\Feature\TRegx\CleanRegex\_flags;

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
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::inject('Foo@', ['Bar'], 'i');

        // when
        $flagIsAdded = $pattern->test('foobar');

        // then
        $this->assertTrue($flagIsAdded);
    }

    /**
     * @test
     */
    public function shouldBuild_template()
    {
        // given
        $pattern = Pattern::template('Foo&', 'i')->literal('Bar')->build();

        // when
        $flagIsAdded = $pattern->test('foobar');

        // then
        $this->assertTrue($flagIsAdded);
    }
}
