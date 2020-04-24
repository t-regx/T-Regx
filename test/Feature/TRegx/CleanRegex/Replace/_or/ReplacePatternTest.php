<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\_or;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_with()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->returningOtherwise('otherwise')->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_withReferences()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->returningOtherwise('otherwise')->withReferences('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_with_orElse()
    {
        // when
        $result = pattern('Foo')
            ->replace('Bar')
            ->first()
            ->otherwise(function (string $subject) {
                $this->assertEquals('Bar', $subject);
                return 'otherwise';
            })
            ->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_with_orThrow()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first()->throwingOtherwise(CustomSubjectException::class);

        // when
        try {
            $replacePattern->with('');
        } catch (CustomSubjectException $e) {
            // then
            $this->assertEquals("Replacements were supposed to be performed, but subject doesn't match the pattern", $e->getMessage());
            $this->assertEquals('Bar', $e->subject);
        }
    }

    /**
     * @test
     */
    public function shouldReturn_callback()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->returningOtherwise('otherwise')->callback(Functions::constant(''));

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
        $result = $replacePattern->returningOtherwise('otherwise')->by()->map([]);

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
        $result = $replacePattern->returningOtherwise('otherwise')->by()->group(1)->map([])->orThrow();

        // then
        $this->assertEquals('otherwise', $result);
    }
}
