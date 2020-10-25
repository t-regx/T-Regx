<?php
namespace Test\Functional\TRegx\SafeRegex;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ClassWithToString;
use Test\Utils\Functions;
use Test\Warnings;
use TRegx\SafeRegex\Exception\CompilePregException;
use TRegx\SafeRegex\Exception\InvalidReturnValueException;
use TRegx\SafeRegex\Exception\MalformedPatternException;
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
        $this->expectException(CompilePregException::class);
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

    /**
     * @test
     */
    public function shouldGetLastErrorMessage()
    {
        // given
        $this->causeRuntimeWarning();

        // when
        $error = preg::last_error();
        $message = preg::last_error_msg();
        $constant = preg::last_error_constant();

        // then
        $this->assertEquals(PREG_BAD_UTF8_ERROR, $error);
        $this->assertEquals('Malformed UTF-8 characters, possibly incorrectly encoded', $message);
        $this->assertEquals('PREG_BAD_UTF8_ERROR', $constant);
    }

    /**
     * @test
     */
    public function shouldQuoteHash()
    {
        // when
        $quoted = preg::quote('Hello # there');

        // then
        $this->assertEquals('Hello \# there', $quoted);
    }

    /**
     * @test
     */
    public function shouldQuoteHashWithDelimiter()
    {
        // when
        $quoted = preg::quote('Hello # % there', '%');

        // then
        $this->assertEquals('Hello \# \% there', $quoted);
    }

    /**
     * @test
     */
    public function shouldQuoteHashWithHashDelimiter()
    {
        // when
        $quoted = preg::quote('Hello # % there', '#');

        // then
        $this->assertEquals('Hello \# % there', $quoted);
    }

    /**
     * @test
     */
    public function shouldReplaceWithStringObject()
    {
        // when
        $result = preg::replace_callback('/valid/', function () {
            return new ClassWithToString("replaced");
        }, 'valid');

        // then
        $this->assertEquals("replaced", $result);
    }

    /**
     * @test
     */
    public function shouldQuoteThrow_forInvalidDelimiter_long()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Delimiter must be one alpha-numeric character');

        // when
        preg::quote('Hello # % there', '##');
    }

    /**
     * @test
     */
    public function shouldQuoteThrow_forInvalidDelimiter_empty()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Delimiter must be one alpha-numeric character');

        // when
        preg::quote('Hello # % there', '');
    }

    /**
     * @test
     */
    public function shouldThrowRuntimeException_withPregPattern()
    {
        // given
        $patterns = [
            '/./'  => Functions::constant('a'),
            '/a(/' => Functions::constant('b'),
        ];

        // when
        try {
            preg::replace_callback_array($patterns, 'word');
        } catch (MalformedPatternException $exception) {
            // then
            $this->assertEquals(['/./', '/a(/'], $exception->getPregPattern());
        }
    }

    /**
     * @test
     */
    public function shouldThrowInvalidReturnValueException_withPregPattern()
    {
        // given
        $patterns = [
            '/./' => Functions::constant('a'),
            '/a/' => Functions::constant(new \stdClass()),
        ];

        // when
        try {
            preg::replace_callback_array($patterns, 'word');
        } catch (InvalidReturnValueException $exception) {
            // then
            $this->assertEquals(['/./', '/a/'], $exception->getPregPattern());
        }
    }
}
