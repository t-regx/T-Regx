<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by;

use PHPUnit\Framework\TestCase;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnDefault_onNonReplaced_by()
    {
        // when
        $result = pattern('Bar')->replace('Foo')->all()->by()->map([]);

        // then
        $this->assertEquals('Foo', $result);
    }

    /**
     * @test
     */
    public function shouldReturnDefault_onNonReplaced_with()
    {
        // when
        $result = pattern('Bar')->replace('Foo')->all()->with('');

        // then
        $this->assertEquals('Foo', $result);
    }

    /**
     * @test
     */
    public function shouldReturnDefault_onNonReplaced_withReferences()
    {
        // when
        $result = pattern('Bar')->replace('Foo')->all()->withReferences('');

        // then
        $this->assertEquals('Foo', $result);
    }

    /**
     * @test
     */
    public function shouldReturnDefault_onNonReplaced_callback()
    {
        // when
        $result = pattern('Bar')->replace('Foo')->all()->callback(function () {
            return '';
        });

        // then
        $this->assertEquals('Foo', $result);
    }
}
