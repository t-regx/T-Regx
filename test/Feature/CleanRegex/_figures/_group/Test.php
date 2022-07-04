<?php
namespace Test\Feature\CleanRegex\_figures\_group;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldGroupNotExist()
    {
        // given
        $pattern = Pattern::of('Foo');
        $match = $pattern->match('Foo');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistLiteral()
    {
        // given
        $pattern = Pattern::literal('Foo');
        $match = $pattern->match('Foo');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistAlteration()
    {
        // given
        $pattern = Pattern::alteration(['FooBar', 'Foo']);
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistInject()
    {
        // given
        $pattern = Pattern::inject('@', ['Foo']);
        $match = $pattern->match('Foo');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistMask()
    {
        // given
        $pattern = Pattern::mask('*Bar', ['*' => 'FooBar|Foo']);
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistTemplateLiteral()
    {
        // given
        $pattern = Pattern::template('@Bar')->literal('Foo');
        $match = $pattern->match('Foo');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistTemplateAlteration()
    {
        // given
        $pattern = Pattern::template('@Bar')->alteration(['FooBar', 'Foo']);
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistTemplateMask()
    {
        // given
        $pattern = Pattern::template('@Bar')->mask('*', ['*' => 'FooBar|Foo']);
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistTemplatePattern()
    {
        // given
        $pattern = Pattern::template('@Bar')->pattern('FooBar|Foo');
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistBuilderLiteral()
    {
        // given
        $pattern = Pattern::builder('@Bar')->literal('Foo')->build();
        $match = $pattern->match('Foo');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistBuilderMask()
    {
        // given
        $pattern = Pattern::builder('@Bar')->mask('*', ['*' => 'FooBar|Foo'])->build();
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistBuilderAlteration()
    {
        // given
        $pattern = Pattern::builder('@Bar')->alteration(['FooBar', 'Foo'])->build();
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }

    /**
     * @test
     */
    public function shouldGroupNotExistBuilderPattern()
    {
        // given
        $pattern = Pattern::builder('@Bar')->pattern('FooBar|Foo')->build();
        $match = $pattern->match('FooBar');
        // when, then
        $this->assertGroupMissing($match, 1);
    }
}
