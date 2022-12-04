<?php
namespace Test\Feature\CleanRegex\noAutoCapture;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCaseConditional;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Pcre;

class PcrePatternTest extends TestCase
{
    use TestCasePasses, AssertsGroup, AssertsPattern, TestCaseConditional;

    /**
     * @test
     */
    public function shouldCast()
    {
        // given
        $pattern = PcrePattern::of('/(Foo){1,2}/ni');
        // when
        $string = (string)$pattern;
        // then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertSame('/(Foo){1,2}/ni', $string);
        } else if (Pcre::pcre2()) {
            $this->assertSame('/(?n)(Foo){1,2}/i', $string);
        } else {
            $this->assertSame('/(?:Foo){1,2}/i', $string);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreDelimiterInPattern()
    {
        // given
        $pattern = PcrePattern::of('/(Foo)/(Bar)/ni');
        // when
        $string = (string)$pattern;
        // then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertSame('/(Foo)/(Bar)/ni', $string);
        } else if (Pcre::pcre2()) {
            $this->assertSame('/(?n)(Foo)/(Bar)/i', $string);
        } else {
            $this->assertSame('/(?:Foo)/(?:Bar)/i', $string);
        }
    }

    /**
     * @test
     */
    public function shouldEnableNoAutoCapture()
    {
        // given
        $pattern = PcrePattern::of('/pattern/nin');
        // when
        $pattern->test('value');
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldNotCapture()
    {
        // given
        $pattern = PcrePattern::of('/(pattern)/n');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertGroupTexts($matcher->first(), []);
        $this->assertGroupMissing($matcher, 1);
    }

    /**
     * @test
     */
    public function shouldNotCaptureMixedWithOtherFlags()
    {
        // given
        $pattern = PcrePattern::of('/(pattern)/inm');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertConsumesFirst('pattern', $pattern);
        $this->assertGroupMissing($matcher, 1);
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSetting()
    {
        // given
        $pattern = PcrePattern::of('%(?xn)(foo)%');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
        if (Pcre::pcre2()) {
            $this->assertPatternIs('%(?xn)(foo)%', $pattern);
        } else {
            $this->assertPatternIs('%(?x)(?:foo)%', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreInnerOptionSetting()
    {
        // given
        $pattern = PcrePattern::of('%(?xn:(foo))%');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
        if (Pcre::pcre2()) {
            $this->assertPatternIs('%(?xn:(foo))%', $pattern);
        } else {
            $this->assertPatternIs('%(?x:(?:foo))%', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSettingReset()
    {
        // given
        $pattern = PcrePattern::of('#(?^xn)(foo)#');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirst('foo', $pattern);
            $this->assertPatternIs('#(?^xn)(foo)#', $pattern);
        } else {
            $this->assertPatternIs('#(?^x)(?:foo)#', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSettingInject()
    {
        // given
        $pattern = PcrePattern::inject('%(?xn)(foo:@)%', ['value']);
        // when, then
        $this->assertConsumesFirst('foo:value', $pattern);
        if (Pcre::pcre2()) {
            $this->assertPatternIs('%(?xn)(foo:(?>value))%', $pattern);
        } else {
            $this->assertPatternIs('%(?x)(?:foo:(?>value))%', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnorePlaceholders()
    {
        // given
        $pattern = PcrePattern::of('/foo:@/');
        // when, then
        $this->assertConsumesFirst('foo:@', $pattern);
    }
}
