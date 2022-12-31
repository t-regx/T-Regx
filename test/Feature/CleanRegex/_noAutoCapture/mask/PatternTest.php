<?php
namespace Test\Feature\CleanRegex\_noAutoCapture\mask;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldNoAutoCapture()
    {
        // given
        $pattern = Pattern::mask('*', [
            '*' => '(?n)(foo),(?<name>bar)'
        ]);
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldNoAutoCaptureModifier()
    {
        // given
        $pattern = Pattern::mask('*', [
            '*' => '(foo),(?<name>bar)'
        ], 'n');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldNoAutoCaptureTemplateModifier()
    {
        // given
        $pattern = Pattern::template('^@$', 'n')->mask('*', [
            '*' => '(foo),(?<name>bar)'
        ]);
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldNoAutoCaptureTemplateInnerOption()
    {
        // given
        $pattern = Pattern::template('^(?n:@)$')->mask('*', [
            '*' => '(foo),(?<name>bar)'
        ]);
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldNoAutoCaptureTemplateOuterOption()
    {
        // given
        $pattern = Pattern::template('^(?n)@$')->mask('*', [
            '*' => '(foo),(?<name>bar)'
        ]);
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldAutoCapture()
    {
        // given
        $pattern = Pattern::mask('*', [
            '*' => '(?n)(?-n)(foo),(?<name>bar)'
        ]);
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'foo', $pattern);
    }
}
