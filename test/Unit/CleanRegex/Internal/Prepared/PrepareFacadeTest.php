<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\IdentityParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuildPattern()
    {
        // when
        $pattern = PrepareFacade::build(new IdentityParser('Foo', '/'), false, '');

        // then
        $this->assertSame('/Foo/', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // when
        $pattern = PrepareFacade::build(new IdentityParser('/#', '%'), false, '');

        // then
        $this->assertSame('%/#%', $pattern->delimited());
    }

    /**
     * @test
     */
    public function shouldParsePcreDelimiter()
    {
        // when
        $pattern = PrepareFacade::build(new IdentityParser('~Foo~', '~'), true, '');

        // then
        $this->assertSame('~Foo~', $pattern->delimited());
    }
}
