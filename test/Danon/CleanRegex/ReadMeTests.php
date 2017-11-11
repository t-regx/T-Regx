<?php
namespace Danon\CleanRegex;

use Danon\CleanRegex\Match\Match;
use PHPUnit\Framework\TestCase;

class ReadMeTests extends TestCase
{
    /**
     * @test
     */
    public function cleanerApi()
    {
        // when
        $result = pattern('[a-z0-9]')->replace('Hello, world')->with('*');

        // then
        $this->assertEquals('H****, ****', $result);
    }

    /**
     * @test
     */
    public function match()
    {
        // when
        $result = pattern('[aeiouy]')->matches('Computer');

        // then
        $this->assertTrue($result, "Failed asserting that pattern matches the subject");
    }

    /**
     * @test
     */
    public function allMatches()
    {
        // when
        $result = pattern('\d+ ?')->match('192 168 172 14')->all();

        // then
        $this->assertEquals(['192', '168', '172', '14'], $result);
    }

    /**
     * @test
     */
    public function retrieve()
    {
        // when
        $result = pattern('[a-zA-Z]+')->match('Robert likes trains')->first();

        // then
        $this->assertEquals('Robert', $result);
    }

    /**
     * @test
     */
    public function iterate()
    {
        // when + then
        pattern('\d+ ?')
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
    public function replaceStrings()
    {
        // when
        $result = pattern('er|ab|ay|ey')->replace('P. Sherman, 42 Wallaby way, Sydney')->with('*');

        // then
        $this->assertEquals('P. Sh*man, 42 Wall**y w**, Sydn**', $result);
    }

    /**
     * @test
     */
    public function replaceLiterally()
    {
        // when
        $result = pattern('\d+')->replace('600 700 800')->with('Number:$1');

        // then
        $this->assertEquals('Number:$1 Number:$1 Number:$1', $result);
    }

    /**
     * @test
     */
    public function replaceCallbacks()
    {
        // given
        $pattern = '(http|ftp)://(?<host>[a-z]+\.(com|org))';
        $subject = 'Links: http://google.com and ftp://some.org.';

        // when
        $result = pattern($pattern)
            ->replace($subject)
            ->callback(function (Match $match) {
                return $match->group('host');
            });

        // then
        $this->assertEquals($result, 'Links: google.com and some.org.');
    }

}
