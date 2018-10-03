<?php
namespace Test\Integration\TRegx\CleanRegex;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ClassWithDefaultConstructor;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
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
        $someDataFromUser = 'word';

        // when
        $all = pattern('\d{3}')->match('My phone is 456-232-123')->all();
        $replace = pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way')->all()->with('__');

        $replaceCallback = pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way')->first()->callback(function (Match $m) {
            return '<' . strtoupper($m->text()) . '>';
        });
        $bareCallback = pattern('er|ab|ay')->replace('P. Sherman, 42 Wallaby way')->first()->callback('strtoupper');

        $forFirst = pattern('word')
            ->match($someDataFromUser)
            ->forFirst('strtoupper')//
            ->orThrow(InvalidArgumentException::class);

        $subject = '192mm and 168cm or 18mm and 12cm';

        $pattern = '(?<value>\d+)(?<unit>cm|mm)';
        pattern($pattern)->match($subject)->iterate(function (Match $match) {
            if ($match != '168cm') return;

            $this->assertEquals('168cm', (string)$match);
            $this->assertEquals('168', $match->group('value'));
            $this->assertEquals('cm', $match->group(2));
            $this->assertEquals(10, $match->offset());

            $this->assertEquals('cm', $match->group('unit')->text());
            $this->assertEquals(13, $match->group('unit')->offset());
            $this->assertEquals(2, $match->group('unit')->index());
            $this->assertEquals('unit', $match->group(2)->name());

            $this->assertEquals(['168', 'cm'], $match->groups());
            $this->assertEquals(['value' => '168', 'unit' => 'cm'], $match->namedGroups());
            $this->assertEquals(['value', 'unit'], $match->groupNames());
            $this->assertFalse($match->hasGroup('val'));

            $this->assertEquals('192mm and 168cm or 18mm and 12cm', $match->subject());
            $this->assertEquals(['192mm', '168cm', '18mm', '12cm'], $match->all());
            $this->assertEquals(['192', '168', '18', '12'], $match->group('value')->all());
        });

        $groupAll = pattern($pattern)->match($subject)->group('value')->all();
        $groupFirst = pattern($pattern)->match($subject)->group('value')->first();
        $stringSplit = pattern($pattern)->match($subject)->first('str_split');
        $stringLength = pattern($pattern)->match($subject)->first('strlen');

        // then
        $this->assertEquals(['456', '232', '123'], $all);
        $this->assertEquals('P. Sh__man, 42 Wall__y w__', $replace);
        $this->assertEquals('P. Sh<ER>man, 42 Wallaby way', $replaceCallback);
        $this->assertEquals('P. ShERman, 42 Wallaby way', $bareCallback);
        $this->assertEquals('WORD', $forFirst);
        $this->assertEquals(['192', '168', '18', '12'], $groupAll);
        $this->assertEquals('192', $groupFirst);
        $this->assertEquals(['1', '9', '2', 'm', 'm'], $stringSplit);
        $this->assertEquals(5, $stringLength);
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
            $this->assertStringStartsWith('preg_replace_callback(): Compilation failed: missing ) at offset 7', $e->getMessage());
        }
        if (preg::match('/\s+/', $input) === false) { // Never happens
        }
    }

    /**
     * @test
     */
    public function replaceUsingCallbacks()
    {
        // when
        $result = pattern('[A-Z][a-z]+')
            ->replace('Some words are Capitalized, and those will be All Caps')
            ->all()
            ->callback('strtoupper');

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
    public function retrieveOnlyFew()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->only(2);

        // then
        $this->assertEquals(['Robert', 'likes'], $result);
    }

    /**
     * @test
     */
    public function retrieveFirstWithCallbackSplit()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->first(function (Match $match) {
            $name = $match->text();
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
        pattern('\d+(?<unit>[ckm]?m)')
            ->match('192cm 168m 172km 14mm')
            ->iterate(function (Match $match) {
                if ($match->text() != '172km') return;

                // gets the match
                $this->assertEquals('172km', $match->text());
                $this->assertEquals('172km', (string)$match);

                // gets the match offset
                $this->assertEquals(11, $match->offset());

                // gets group
                $this->assertEquals('km', $match->group('unit'));

                // gets other matches
                $this->assertEquals(['192cm', '168m', '172km', '14mm'], $match->all());
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
                return $match->text() * 2;
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
    public function replaceOnlyFewStrings()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')
            ->only(2)
            ->with('__');

        // then
        $this->assertEquals('P. Sh__man, 42 Wall__y way, Sydney', $result);
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
    public function controllingUnmatched()
    {
        // when
        $orElse = pattern('x')
            ->match('asd')
            ->forFirst(function (Match $match) {
                return '*' . $match . '*';
            })
            ->orElse(function (NotMatched $m) {
                return 'Subject ' . $m->subject() . ' unmatched';
            });

        $orReturn = pattern('x')
            ->match('asd')
            ->forFirst(function (Match $match) {
                return '*' . $match . '*';
            })
            ->orReturn('Unmatched :/');

        $orThrow = pattern('(x|asd)')
            ->match('asd')
            ->forFirst(function (Match $match) {
                return 'Matched "' . $match . '"';
            })
            ->orThrow(ClassWithDefaultConstructor::class);

        // then
        $this->assertEquals('Subject asd unmatched', $orElse);
        $this->assertEquals('Unmatched :/', $orReturn);
        $this->assertEquals('Matched "asd"', $orThrow);
    }

    /**
     * @test
     */
    public function splitEx()
    {
        // when
        $split = pattern(',')->split('Foo,Bar,Cat')->ex();

        // then
        $this->assertEquals(['Foo', 'Bar', 'Cat'], $split);
    }

    /**
     * @test
     */
    public function splitInc()
    {
        // when
        $split = pattern('(\|)')->split('One|Two|Three')->inc();

        // then
        $this->assertEquals(['One', '|', 'Two', '|', 'Three'], $split);
    }

    /**
     * @test
     */
    public function splitFilteredEx()
    {
        // when
        $split = pattern('\.')->split('192..168...18.23')->filter()->ex();

        // then
        $this->assertEquals(['192', '168', '18', '23'], $split);
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
                $this->assertEquals('Robert', $match->text());
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
