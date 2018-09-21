<?php
namespace Test\Integration\CleanRegex;

use CleanRegex\Match\Details\Match;
use CleanRegex\Pattern;
use PHPUnit\Framework\TestCase;

class ReadMeTest extends TestCase
{
    /**
     * @test
     */
    public function replaceUsingCallbacks()
    {
        // when
        $result = pattern('[A-Z][a-z]+')
            ->replace('Some words are Capitalized, and those will be All Caps')
            ->all()
            ->callback(function (Match $match) {
                return strtoupper($match);
            });

        // then
        $this->assertEquals('SOME words are CAPITALIZED, and those will be ALL CAPS', $result);
    }

    /**
     * @test
     */
    public function replaceUsingCallbacksWithGroups()
    {
        // given
        $subject = 'Links: http://first.com and http://second.org.';

        // when
        $result = pattern('http://(?<name>[a-z]+)\.(com|org)')
            ->replace($subject)
            ->first()
            ->callback(function (Match $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals('Links: first and http://second.org.', $result);
    }

    /**
     * @test
     */
    public function match()
    {
        // when
        $result = pattern('[A-Z][a-z]+')->matches('Computer');

        // then
        $this->assertTrue($result, "Failed asserting that pattern matches the subject");
    }

    /**
     * @test
     */
    public function allMatches()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->all();

        // then
        $this->assertEquals(['Robert', 'likes', 'trains'], $result);
    }

    /**
     * @test
     */
    public function retrieveFirst()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->first();

        // then
        $this->assertEquals('Robert', $result);
    }

    /**
     * @test
     */
    public function retrieveFirstWithCallbackSplit()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->first(function (Match $match) {
            $name = $match->match();
            return str_split($name);
        });

        // then
        $this->assertEquals(['R', 'o', 'b', 'e', 'r', 't',], $result);
    }

    public function retrieveGroups()
    {
        // when
        $result = pattern('(?<hour>\d\d)?:(?<minute>\d\d)')->match('14:15, 16:30, 24:05 or none __:30')->group('hour');

        // then
        $this->assertEquals(['14', '16', '24', null], $result);
    }

    /**
     * @test
     */
    public function retrieveFirstWithCallbackOffset()
    {
        // when
        $result = pattern('[a-z]+$')
            ->match('Robert likes trains')
            ->first(function (Match $match) {
                return $match . ' at ' . $match->offset();
            });

        // then
        $this->assertEquals('trains at 13', $result);
    }

    /**
     * @test
     */
    public function iterate()
    {
        // when + then
        pattern('\d+')
            ->match('192 168 172 14')
            ->iterate(function (Match $match) {
                if ($match->match() != '172') return;

                // gets the match
                $this->assertEquals("172", $match->match());
                $this->assertEquals("172", (string)$match);

                // gets the match offset
                $this->assertEquals(8, $match->offset());

                // gets the group index
                $this->assertEquals(2, $match->index());

                // gets other groups
                $this->assertEquals(['192', '168', '172', '14'], $match->all());
            });
    }

    /**
     * @test
     */
    public function map()
    {
        // when
        $map = pattern('\d+')
            ->match('192 168 172 14')
            ->map(function (Match $match) {
                if ($match == '168') {
                    return null;
                }
                return $match->match() * 2;
            });

        // then
        $this->assertEquals([384, null, 344, 28], $map);
    }

    /**
     * @test
     */
    public function countVowels()
    {
        // when
        $amount = pattern('[aeiouy]')->count('Computer');

        // then
        $this->assertEquals('There are 3 vowels', "There are $amount vowels");
    }

    /**
     * @test
     */
    public function countVowelsWithMatch()
    {
        // when
        $amount = pattern('[aeiouy]')->match('Computer')->count();

        // then
        $this->assertEquals('There are 3 vowels', "There are $amount vowels");
    }

    /**
     * @test
     */
    public function replaceAllStrings()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->all()
            ->with('__');

        // then
        $this->assertEquals('P. Sh__man, 42 Wall__y w__, Sydn__', $result);
    }

    /**
     * @test
     */
    public function replaceFirstStrings()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->first()
            ->with('__');

        // then
        $this->assertEquals('P. Sh__man, 42 Wallaby way, Sydney', $result);
    }

    /**
     * @test
     */
    public function replaceCallbacks()
    {
        // given
        $pattern = 'http://(?<name>[a-z]+)\.(com|org)';
        $subject = 'Links: http://google.com and http://other.org.';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->all()
            ->callback(function (Match $match) {
                return $match->group('name');
            });

        // then
        $this->assertEquals($result, 'Links: google and other.');
    }

    /**
     * @test
     */
    public function filterArray()
    {
        // when
        $result = pattern('^[A-Z][a-z]+$')->filter([
            'Mark',
            'Robert',
            'asdczx',
            'Jane',
            'Stan123'
        ]);

        // then
        $this->assertEquals(['Mark', 'Robert', 'Jane'], $result);
    }

    /**
     * @test
     */
    public function firstMatchWithDetail()
    {
        // when
        pattern('(?<capital>[A-Z])(?<lowercase>[a-z]+)')
            ->match('Robert Likes Trains')
            ->first(function (Match $match) {
                // then
                $this->assertEquals('Robert', $match->match());
                $this->assertEquals('Robert', (string)$match);

                $this->assertEquals('Robert Likes Trains', $match->subject());

                $this->assertEquals(0, $match->index());
                $this->assertEquals(0, $match->offset());

                $this->assertEquals(['Robert', 'Likes', 'Trains'], $match->all());

                $this->assertEquals('R', $match->group('capital'));
                $this->assertEquals('R', $match->group(1));
                $this->assertEquals('obert', $match->group('lowercase'));
                $this->assertEquals('obert', $match->group(2));

                $this->assertEquals(['capital', 'lowercase'], $match->groupNames());

                $this->assertEquals(true, $match->hasGroup('capital'));

                $this->assertEquals(true, $match->matched('capital'));

                $this->assertEquals(['capital' => 'R', 'lowercase' => 'obert'], $match->namedGroups());
            });
    }

    /**
     * @test
     * @dataProvider validPatterns
     * @param string $pattern
     * @param bool $expectedValid
     * @param bool $expectedUsable
     */
    public function validatePattern(string $pattern, bool $expectedValid, bool $expectedUsable)
    {
        // when
        $valid = pattern($pattern)->is()->valid();
        $usable = pattern($pattern)->is()->usable();

        // then
        $this->assertEquals($expectedValid, $valid);
        $this->assertEquals($expectedUsable, $usable);
    }

    function validPatterns()
    {
        return [
            ['/[a-z]/im', true, true],
            ['[a-z]+', false, true],
            ['//[a-z]', false, false],
            ['/(unclosed/', false, false],
        ];
    }

    /**
     * @test
     */
    public function delimiter()
    {
        // when
        $result1 = pattern('[A-Z]/[a-z]')->delimitered();
        $result2 = pattern('[0-9]#[0-9]')->delimitered();

        // then
        $this->assertEquals('#[A-Z]/[a-z]#', $result1);
        $this->assertEquals('/[0-9]#[0-9]/', $result2);
    }

    /**
     * @test
     */
    public function quotePattern()
    {
        // when
        $result = pattern('Your IP is [192.168.12.20] (local\tcp)')->quote();

        // then
        $this->assertEquals('Your IP is \[192\.168\.12\.20\] \(local\\\\tcp\)', $result);
    }

    /**
     * @test
     */
    public function factoryMethodOf()
    {
        // when
        $instance = Pattern::of('[A-Z][a-z]+');

        // then
        $this->assertEquals('/[A-Z][a-z]+/', $instance->delimitered());
    }
}
