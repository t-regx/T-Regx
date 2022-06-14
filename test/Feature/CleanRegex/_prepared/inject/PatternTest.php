<?php
namespace Test\Feature\CleanRegex\_prepared\inject;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider templatesWithPlaceholder
     * @param string $pattern
     * @param string $expected
     */
    public function shouldUsePlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = Pattern::inject($pattern, ['X']);

        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithPlaceholder(): array
    {
        return [
            'standard'                               => ['You/her @ her?', '#You/her X her?#'],
            'comment (but no "x" flag)'              => ["You/her #@\n her?", "%You/her #X\n her?%"],
            'comment ("x" flag, but also "-x" flag)' => ["You/her (?x:(?-x:#@\n)) her?", "%You/her (?x:(?-x:#X\n)) her?%"],
        ];
    }

    /**
     * @test
     * @dataProvider templatesWithoutPlaceholders
     * @param string $pattern
     * @param string $expected
     */
    public function shouldNotMistakeLiteralForPlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = Pattern::inject($pattern, []);

        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithoutPlaceholders(): array
    {
        return [
            "placeholder '@' in []"      => ['You/her [@] her?', '#You/her [@] her?#'],
            "placeholder '@' in \Q\E"    => ['You/her \Q@\E her?', '#You/her \Q@\E her?#'],
            "placeholder '@' escaped"    => ['You/her \@ her?', '#You/her \@ her?#'],
            "placeholder '@' in comment" => ["You/her (?x:#@\n) her?", "%You/her (?x:#@\n) her?%"],
            "placeholder '@' in control" => ["You/her \c@ her?", "#You/her \c@ her?#"],
        ];
    }

    /**
     * @test
     */
    public function shouldIgnorePlaceholderInGroupComment()
    {
        // when
        $pattern = Pattern::inject('foo(?#@', []);

        // then
        $this->assertSamePattern('/foo(?#@/', $pattern);
    }

    /**
     * @test
     */
    public function shouldIncludeRemainderInPattern()
    {
        // when
        $pattern = Pattern::inject("(?x)#@\n", []);

        // then
        $this->assertSamePattern("/(?x)#@\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInPattern
     */
    public function shouldCloseRemainder()
    {
        // when
        $pattern = Pattern::inject("(#@\n(?x)#@\n)#@\n", ['One', 'Three']);

        // then
        $this->assertConsumesFirst("#One\n#Three\n", $pattern);
        $this->assertSamePattern("/(#One\n(?x)#@\n)#Three\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldCloseRemainder
     */
    public function shouldCloseRemainderAndParentPattern()
    {
        // when
        $pattern = Pattern::inject("(?x:(?x))#@\n", ['Bar']);

        // then
        $this->assertConsumesFirst("#Bar\n", $pattern);
        $this->assertSamePattern("/(?x:(?x))#Bar\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInPattern
     */
    public function shouldIncludeRemainderInSubpattern()
    {
        // when
        $pattern = Pattern::inject("(?x)(#@\n)", []);

        // then
        $this->assertSamePattern("/(?x)(#@\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInPattern
     */
    public function shouldIncludeRemainderInNextPattern()
    {
        // when
        $pattern = Pattern::inject("(?x)()#@\n", []);

        // then
        $this->assertSamePattern("/(?x)()#@\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInSubpattern
     * @depends shouldIncludeRemainderInNextPattern
     */
    public function shouldIncludeRemainderInNextSubpattern()
    {
        // when
        $pattern = Pattern::inject("(?x)()(#@\n)", []);

        // then
        $this->assertSamePattern("/(?x)()(#@\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextSubpattern
     */
    public function shouldIncludeRemainderInNextSubpatternBeforeGroupNull()
    {
        // when
        $pattern = Pattern::inject("(?x)(?:)(#@\n)", []);

        // then
        $this->assertSamePattern("/(?x)(?:)(#@\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextSubpattern
     */
    public function shouldIncludeRemainderInNextSubpatternBeforeGroupNullShort()
    {
        // when
        $pattern = Pattern::inject("(?x)(?)(#@\n)", []);

        // then
        $this->assertSamePattern("/(?x)(?)(#@\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextSubpattern
     */
    public function shouldIncludeRemainderInNextSubpatternBeforeGroupComment()
    {
        // when
        $pattern = Pattern::inject("(?x)(?#)#@\n", []);

        // then
        $this->assertSamePattern("/(?x)(?#)#@\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextPattern
     */
    public function shouldCloseRemainderInNextPattern()
    {
        // when
        $pattern = Pattern::inject("((?x))#@\n", ['Bar']);

        // then
        $this->assertSamePattern("/((?x))#Bar\n/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextSubpattern
     */
    public function shouldCloseRemainderInNextSubpattern()
    {
        // when
        $pattern = Pattern::inject("((?x))(#@\n)", ['Bar']);

        // then
        $this->assertSamePattern("/((?x))(#Bar\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextSubpattern
     */
    public function shouldIncludeManyRemainders()
    {
        // when
        $pattern = Pattern::inject("(?x)(?-x)(#@\n)", ['Bar']);

        // then
        $this->assertSamePattern("/(?x)(?-x)(#Bar\n)/", $pattern);
    }

    /**
     * @test
     * @depends shouldIncludeRemainderInNextSubpattern
     */
    public function shouldCancelManyRemainders()
    {
        // when
        $pattern = Pattern::inject("((?x)(?-x))#@\n", ['Bar']);

        // then
        $this->assertSamePattern("/((?x)(?-x))#Bar\n/", $pattern);
    }
}
