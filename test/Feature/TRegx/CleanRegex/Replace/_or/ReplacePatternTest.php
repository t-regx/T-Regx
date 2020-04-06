<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\_or;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use Test\Utils\Functions;

class ReplacePatternTest extends TestCase
{
    // TODO user data providers for all those test cases

    /**
     * @test
     */
    public function shouldReturn_with()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->orReturn('otherwise')->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_with_orElse()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->orElse(function (string $subject) {
            $this->assertEquals('Bar', $subject);
            return 'otherwise';
        })->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_with_orThrow()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Replacements were supposed to be performed, but subject doesn't match the pattern");

        // when
        $replacePattern->orThrow(CustomException::class)->with('');
    }

    /**
     * @test
     */
    public function shouldReturn_withReferences()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->orReturn('otherwise')->withReferences('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_callback()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->orReturn('otherwise')->callback(Functions::constant(''));

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_by_map()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->orReturn('otherwise')->by()->map([]);

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_by_group_map()
    {
        // given
        $replacePattern = pattern('(Foo)')->replace('Bar')->first();

        // when
        $result = $replacePattern->orReturn('otherwise')->by()->group(1)->map([])->orThrow();

        // then
        $this->assertEquals('otherwise', $result);
    }
}
