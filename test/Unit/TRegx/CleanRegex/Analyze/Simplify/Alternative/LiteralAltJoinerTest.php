<?php
namespace Test\Unit\TRegx\CleanRegex\Analyze\Simplify\Alternative;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Analyze\Simplify\Alternative\LiteralAltJoiner;
use TRegx\CleanRegex\Analyze\Simplify\Model\Alt;
use TRegx\CleanRegex\Analyze\Simplify\Model\AltEnd;
use TRegx\CleanRegex\Analyze\Simplify\Model\AltStart;
use TRegx\CleanRegex\Analyze\Simplify\Model\EscapedLiteral;
use TRegx\CleanRegex\Analyze\Simplify\Model\Literal;

class LiteralAltJoinerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldJoin_onlyNested()
    {
        // given
        $models = [
            new Literal('0'),
            new AltStart(),
            new Literal('a'),
            new AltStart(),
            new Literal('k'),
            new EscapedLiteral('\.'),
            new AltEnd(),
            new Literal('k'),
            new AltEnd(),
            new Literal('z'),
        ];
        $factory = new LiteralAltJoiner($models);

        // when
        $transformed = $factory->join();

        // then
        $expected = [
            new Literal('0'),
            new AltStart(),
            new Literal('a'),
            new Alt([
                new Literal('k'),
                new EscapedLiteral('\.'),
            ]),
            new Literal('k'),
            new AltEnd(),
            new Literal('z'),
        ];
        $this->assertEquals($expected, $transformed);
    }

    /**
     * @test
     */
    public function shouldJoin_jagged()
    {
        // given
        $models = [
            new AltStart(),
            new AltStart(),
            new AltStart(),
            new Literal('a'),
            new AltEnd(),
            new AltEnd(),
            new AltStart(),
            new Literal('k'),
            new AltEnd(),
            new AltEnd(),
        ];
        $factory = new LiteralAltJoiner($models);

        // when
        $transformed = $factory->join();

        // then
        $expected = [
            new AltStart(),
            new AltStart(),
            new Alt([
                new Literal('a'),
            ]),
            new AltEnd(),
            new Alt([
                new Literal('k'),
            ]),
            new AltEnd(),
        ];
        $this->assertEquals($expected, $transformed);
    }

    /**
     * @test
     */
    public function shouldJoin_empty()
    {
        // given
        $models = [
            new AltEnd(),
            new AltStart(),
            new AltEnd(),
        ];
        $factory = new LiteralAltJoiner($models);

        // when
        $transformed = $factory->join();

        // then
        $expected = [
            new AltEnd(),
            new Alt([
            ]),
        ];
        $this->assertEquals($expected, $transformed);
    }
}
