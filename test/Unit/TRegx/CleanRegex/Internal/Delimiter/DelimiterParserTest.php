<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\DuplicateFlagsException;
use TRegx\CleanRegex\Exception\FlagNotAllowedException;
use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;

class DelimiterParserTest extends TestCase
{
    public function delimited()
    {
        return [
            // Standard delimiters
            ['//', '/'],
            [';;', ';'],
            ['/Foo/', '/'],
            ['%Foo%', '%'],
            ['~Foo~', '~'],
            ['+Foo+', '+'],
            ['!Foo!', '!'],
            ['@Foo@', '@'],
            ['_Foo_', '_'],
            [';Foo;', ';'],
            ['`Foo`', '`'],
            ['-Foo-', '-'],
            ['=Foo=', '='],
            [',Foo,', ','],

            // Corner cases
            ['/Foo\/Bar/', '/'],
            ['+Foo\+Bar+', '+'],
            ['/Foo#Bar/', '/'],
            ['#Foo/Bar#', '#'],
            ['/\/Foo/xu', '/'],

            // Flags
            ['/Foo/m', '/'],
            ['/Foo/imsxuADSUXJ', '/'],
            ['#Foo\#Bar#i', '#']
        ];
    }

    /**
     * @test
     * @dataProvider delimited
     * @param string $pattern
     * @param string $delimiter
     */
    public function shouldGetDelimiter(string $pattern, string $delimiter)
    {
        // given
        $delimiterer = new DelimiterParser();

        // when
        $result = $delimiterer->getDelimiter($pattern);

        // then
        $this->assertEquals($delimiter, $result);
    }

    public function notDelimited()
    {
        return [
            [''],
            ['/'],
            ['#'],
            ['a'],
            ['Foo#Bar'],
            ['/Foo'],
            ['/Foo#'],

            // Closable characters should not be treated as delimiters
            ['(__Foo__)'],
            ['[__Foo__]'],
            ['{__Foo__}'],
            ['<__Foo__>'],

            // Flags
            ['/Foo/m4'],
            ['#Foo#$'],
        ];
    }

    /**
     * @test
     * @dataProvider notDelimited
     * @param string $pattern
     */
    public function shouldNotGetDelimiter(string $pattern)
    {
        // given
        $delimiterer = new DelimiterParser();

        // when
        $result = $delimiterer->getDelimiter($pattern);

        // then
        $this->assertNull($result, "Failed asserting that $pattern has no delimiter.");
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidFlags()
    {
        // given
        $delimiter = new DelimiterParser();

        // then
        $this->expectException(FlagNotAllowedException::class);
        $this->expectExceptionMessage("Regular expression flags: ['a', 'f'] are not allowed");

        // when
        $delimiter->getDelimiter('/Foo/amfx');
    }
}
