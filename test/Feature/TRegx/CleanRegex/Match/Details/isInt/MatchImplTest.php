<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchImplTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        $result = pattern('(?<name>-?\w+)')
            ->match('11')
            ->first(function (Detail $match) {
                // when
                return $match->isInt();
            });

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldPseudoInteger_notBeInt_becausePhpSucks()
    {
        // given
        $result = pattern('(.*)', 's')
            ->match('1e3')
            ->first(function (Detail $match) {
                // when
                return $match->isInt();
            });

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldNotBeInteger()
    {
        // given
        pattern('(?<name>\w+)')
            ->match('Foo bar')
            ->first(function (Detail $match) {
                // when
                $result = $match->isInt();

                // then
                $this->assertFalse($result);
            });
    }
}
