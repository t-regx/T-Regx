<?php
namespace Test\Functional\TRegx\SafeRegex;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ClassWithToString;
use Test\Utils\Functions;
use Test\Utils\Warnings;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\InvalidReturnValueException;
use TRegx\SafeRegex\Exception\PregMalformedPatternException;
use TRegx\SafeRegex\Exception\UnicodeOffsetException;
use TRegx\SafeRegex\preg;

/**
 * @coversNothing
 */
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
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_filter()
    {
        // when
        $result = preg::filter([], [], []);

        // then
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_arrayInput_filteredOut()
    {
        // when
        $result = preg::filter('/c/', '', ['a', 'b']);

        // then
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function shouldBeProneToRegexCallbackWarnings()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("No ending delimiter '/' found");

        // when
        preg::replace_callback('/valid/', function () {
            $this->causeMalformedPatternWarning();
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
        $this->assertSame('some user error', $warning['message']);
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
        $this->assertSame(1, $value);
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
        $this->assertSame(PREG_BAD_UTF8_ERROR, $error);
        $this->assertSame('Malformed UTF-8 characters, possibly incorrectly encoded', $message);
        $this->assertSame('PREG_BAD_UTF8_ERROR', $constant);
    }

    /**
     * @test
     */
    public function shouldQuoteHash()
    {
        // when
        $quoted = preg::quote('Hello # there');

        // then
        $this->assertSame('Hello \# there', $quoted);
    }

    /**
     * @test
     */
    public function shouldQuoteHashWithDelimiter()
    {
        // when
        $quoted = preg::quote('Hello # % there', '%');

        // then
        $this->assertSame('Hello \# \% there', $quoted);
    }

    /**
     * @test
     */
    public function shouldQuoteHashWithHashDelimiter()
    {
        // when
        $quoted = preg::quote('Hello # % there', '#');

        // then
        $this->assertSame('Hello \# % there', $quoted);
    }

    /**
     * @test
     */
    public function shouldUnquote()
    {
        // when
        $unquoted = preg::unquote('\[a\-z\]\+');

        // then
        $this->assertSame('[a-z]+', $unquoted);
    }

    /**
     * @test
     * @dataProvider quotable
     * @param string $input
     */
    public function shouldPreserveContract(string $input)
    {
        // when
        $output = preg::unquote(preg::quote($input));

        // then
        $this->assertSame($input, $output);
    }

    function quotable(): array
    {
        return [
            ['https://stackoverflow.com/search?q=preg_match#anchor'],
            ['preg_quote(\'an\y s … \.tri\*ng\') //'],
            ['.\+*?[^]$(){}=!<>|:-'],
            ['\\\\\\'],
            ['\\\\'],
            ['"Quoted?"'],
        ];
    }

    /**
     * @test
     */
    public function shouldNotUnquote_regularCharacters()
    {
        // given
        $input = '\\\' \" \/ \;';

        // when
        $unquoted = preg::unquote($input);

        // then
        $this->assertSame($input, $unquoted);
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
        $this->assertSame("replaced", $result);
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
        } catch (PregMalformedPatternException $exception) {
            // then
            $this->assertSame(['/./', '/a(/'], $exception->getPregPattern());
        }
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidUtf8Offset(): void
    {
        // given
        $this->expectException(UnicodeOffsetException::class);
        $this->expectExceptionMessage('Invalid UTF-8 offset parameter was passed to preg_match()');

        // when
        preg::match('/€/u', '€', $match, 0, 1);
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
            $this->assertSame(['/./', '/a/'], $exception->getPregPattern());
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreMethods_startingWithPreg(): void
    {
        // given
        preg::replace_callback('/foo/', function () {
            @\trigger_error('preg_cheating: welcome');
        }, 'foo');

        // then
        $this->assertTrue(true);
    }
}
