<?php
namespace Test\Feature\CleanRegex\noAutoCapture\pattern;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCaseConditional;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Pcre;

class PatternTest extends TestCase
{
    use TestCasePasses, AssertsGroup, AssertsPattern, TestCaseConditional;

    /**
     * @test
     */
    public function shouldCaptureInTemplatePattern()
    {
        // given
        $pattern = Pattern::template('^@$')->pattern('(foo),(?<name>bar)');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/^(?:(foo),(?<name>bar))$/', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/^(?:(foo),(?<name>bar))$/', $pattern);
        } else {
            $this->assertPatternIs('/^(?:(foo),(?<name>bar))$/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldCaptureInTemplatePatternUnset()
    {
        // given
        $pattern = Pattern::template('^(?-n:@)$', 'n')->pattern('(foo),(?<name>bar)');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/^(?-n:(?:(foo),(?<name>bar)))$/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)^(?-n:(?:(foo),(?<name>bar)))$/', $pattern);
        } else {
            $this->assertPatternIs('/^(?-:(?:(foo),(?<name>bar)))$/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureInTemplatePatternModifier()
    {
        // given
        $pattern = Pattern::template('^@$', 'n')->pattern('(foo),(?<name>bar)');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
        $this->assertGroupMissing($pattern->match('foo'), 2);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/^(?:(foo),(?<name>bar))$/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)^(?:(foo),(?<name>bar))$/', $pattern);
        } else {
            $this->assertPatternIs('/^(?:(?:foo),(?<name>bar))$/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureInTemplatePatternInnerOption()
    {
        // given
        $pattern = Pattern::template('(?n:^@$)')->pattern('(foo),(?<name>bar)');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
        $this->assertGroupMissing($pattern->match('foo'), 2);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?n:^(?:(foo),(?<name>bar))$)/', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n:^(?:(foo),(?<name>bar))$)/', $pattern);
        } else {
            $this->assertPatternIs('/(?:^(?:(?:foo),(?<name>bar))$)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureInTemplatePatternOuterOption()
    {
        // given
        $pattern = Pattern::template('(?n)^@$')->pattern('(foo),(?<name>bar)');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
        $this->assertGroupMissing($pattern->match('foo'), 2);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?n)^(?:(foo),(?<name>bar))$/', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)^(?:(foo),(?<name>bar))$/', $pattern);
        } else {
            $this->assertPatternIs('/(?)^(?:(?:foo),(?<name>bar))$/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldUnsetNoAutoCaptureAfter()
    {
        // given
        $pattern = Pattern::template('^@$', 'n')->pattern('(?-n:(foo),(?<name>bar))');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/^(?:(?-n:(foo),(?<name>bar)))$/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)^(?:(?-n:(foo),(?<name>bar)))$/', $pattern);
        } else {
            $this->assertPatternIs('/^(?:(?-:(foo),(?<name>bar)))$/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldUnsetNoAutoCaptureAfterSettingInnerOption()
    {
        // given
        $pattern = Pattern::template('(?n)^@$')->pattern('(?-n:(foo),(?<name>bar))');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?n)^(?:(?-n:(foo),(?<name>bar)))$/', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)^(?:(?-n:(foo),(?<name>bar)))$/', $pattern);
        } else {
            $this->assertPatternIs('/(?)^(?:(?-:(foo),(?<name>bar)))$/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldUnsetNoAutoCaptureAfterSettingInnerOptionBuilder()
    {
        // given
        $pattern = Pattern::builder('(?n)^@$')->pattern('(?-n:(foo),(?<name>bar))')->build();
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?n)^(?:(?-n:(foo),(?<name>bar)))$/', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)^(?:(?-n:(foo),(?<name>bar)))$/', $pattern);
        } else {
            $this->assertPatternIs('/(?)^(?:(?-:(foo),(?<name>bar)))$/', $pattern);
        }
    }
}
