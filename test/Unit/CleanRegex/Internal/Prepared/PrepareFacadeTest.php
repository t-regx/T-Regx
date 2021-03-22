<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use Test\Utils\Impl\IdentityParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldBuildPattern()
    {
        // when
        $pattern = PrepareFacade::build(new IdentityParser('Foo', '/'), false, '');

        // then
        $this->assertSamePattern('/Foo/', $pattern);
    }

    /**
     * @test
     */
    public function shouldChooseDelimiter()
    {
        // when
        $pattern = PrepareFacade::build(new IdentityParser('/#', '%'), false, '');

        // then
        $this->assertSamePattern('%/#%', $pattern);
    }

    /**
     * @test
     */
    public function shouldParsePcreDelimiter()
    {
        // when
        $pattern = PrepareFacade::build(new IdentityParser('~Foo~', '~'), true, '');

        // then
        $this->assertSamePattern('~Foo~', $pattern);
    }
}
