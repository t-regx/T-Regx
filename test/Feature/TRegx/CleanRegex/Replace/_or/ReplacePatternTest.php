<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\_or;

use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
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
        $result = $replacePattern->orReturn('otherwise')->callback(function () {
            return '';
        });

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     * Call to just one chained functions of `by()` is required, because
     * others `mapDefault()`, `mapIfExists()` all use the same private method.
     * Should all methods in `by()` use different implementations, the tests should be added.
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
}
