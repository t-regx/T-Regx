<?php
namespace Test\Feature\TRegx\CleanRegex;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\Exception\SafeRegexException;
use TRegx\SafeRegex\preg;

class ReadMeTest extends TestCase
{
    /**
     * @test
     */
    public function examples()
    {
        // given
        $s = 'My phone is 456-232-123';

        // when
        $this->assertEquals('456', pattern('\d{3}')->match($s)->first());
        $this->assertEquals(['456', '232', '123'], pattern('\d{3}')->match($s)->all());
        $this->assertEquals(['456', '232'], pattern('\d{3}')->match($s)->only(2));

        $this->assertEquals(['4', '5', '6'], pattern('\d{3}')->match($s)->first('str_split'));
        $this->assertEquals(3, pattern('\d{3}')->match($s)->first('strlen'));

        $replaceAll = pattern('er|ab|ay')
            ->replace('P. Sherman, 42 Wallaby way')
            ->all()
            ->with('__');

        $this->assertEquals('P. Sh__man, 42 Wall__y w__', $replaceAll);

        $replaceFirst = pattern('er|ab|ay')
            ->replace('P. Sherman, 42 Wallaby way')
            ->first()
            ->callback('strtoupper');

        $this->assertEquals('P. ShERman, 42 Wallaby way', $replaceFirst);

        $forFirst = pattern('word')
            ->match('word')
            ->forFirst('strtoupper')
            ->orThrow(InvalidArgumentException::class);

        $this->assertEquals('WORD', $forFirst);
    }

    /**
     * @test
     */
    public function safeRegex()
    {
        // given
        $url = '';
        $input = '';
        $myCallback = 'strtoupper';

        // when
        try {
            if (preg::match_all('/^https?:\/\/(www)?\./', $url) > 0) {
            }

            preg::replace_callback('/(regexp/i', $myCallback, 'I very much like regexps');
        } catch (SafeRegexException $e) {
            $this->assertRegExp(PhpVersionDependent::getUnmatchedParenthesisMessage_ReplaceCallback(7), $e->getMessage());
        }
        if (preg::match('/\s+/', $input) === false) {
            // Never happens
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function factoryMethod_of()
    {
        // when
        $instance = Pattern::of('[A-Z][a-z]+');

        // then
        $this->assertEquals('/[A-Z][a-z]+/', $instance->delimiter());
    }

    /**
     * @test
     */
    public function factoryMethod_globalMethod()
    {
        // when
        $instance = pattern('[A-Z][a-z]+');

        // then
        $this->assertEquals('/[A-Z][a-z]+/', $instance->delimiter());
    }
}
