<?php
namespace Test\Feature\TRegx\CleanRegex\_flags;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

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
    public function shouldBuild_template_literal()
    {
        // given
        $pattern = Pattern::template('Foo@', 'i')->literal('Bar');

        // when
        $flagIsAdded = $pattern->test('foobar');

        // then
        $this->assertTrue($flagIsAdded);
    }

    /**
     * @test
     */
    public function shouldBuild_mask()
    {
        // when
        $pattern = Pattern::mask('Cat:%w', ['%w' => 'Foo'], 'i');

        // then
        $this->assertConsumesFirst('cat:foo', $pattern);
    }
}
