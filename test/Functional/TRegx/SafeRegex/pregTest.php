<?php
namespace Test\Functional\TRegx\SafeRegex;

use PHPUnit\Framework\TestCase;
use Test\Warnings;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    use Warnings;

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_replace()
    {
        // when
        $result = preg::replace([], [], []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_filter()
    {
        // when
        $result = preg::filter([], [], []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_arrayInput_filteredOut()
    {
        // when
        $result = preg::filter('/c/', '', ['a', 'b']);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldBeProneToRegexCallbackWarnings()
    {
        // then
        $this->expectException(CompileSafeRegexException::class);
        $this->expectExceptionMessage("No ending delimiter '/' found");

        // when
        preg::replace_callback('/valid/', function () {
            $this->causeCompileWarning();
            return 'maybe';
        }, 'valid');
    }

    /**
     * @test
     */
    public function shouldBeIndependentOfCallbackWarning()
    {
        // when
        preg::replace_callback('/valid/', function () {
            trigger_error('some other warning', E_USER_WARNING);
            return 'maybe';
        }, 'valid');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function testPregWarningsInsideCallbacksAreTransparent()
    {
        // given
        preg::replace_callback('/./', function () {
            trigger_error('some user error', E_USER_WARNING);
        }, 'Foo');

        // when
        $warning = error_get_last();

        // then
        $this->assertEquals('some user error', $warning['message']);
    }

    /**
     * @test
     * @link https://bugs.php.net/bug.php?id=78853
     */
    public function shouldPregMatchReturn1_onAllPhpVersions()
    {
        // when
        $value = preg::match('/^|\d{1,2}$/', "7");

        // then
        $this->assertEquals(1, $value);
    }
}
