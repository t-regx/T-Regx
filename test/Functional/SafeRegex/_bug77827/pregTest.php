<?php
namespace Test\Functional\SafeRegex\_bug77827;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    /**
     * @dataProvider flags
     */
    public function testMatch(string $flag)
    {
        // when
        preg::match("~foo~$flag", 'foo', $match);
        // then
        $this->assertSame(['foo'], $match);
    }

    /**
     * @dataProvider flags
     */
    public function testMatchAll(string $flag)
    {
        // when
        preg::match_all("~foo~$flag", 'foo', $match);
        // then
        $this->assertSame([['foo']], $match);
    }

    /**
     * @dataProvider flags
     */
    public function testReplace(string $flag)
    {
        // when
        $result = preg::replace("~foo~$flag", 'bar', 'foo');
        // then
        $this->assertSame('bar', $result);
    }

    /**
     * @dataProvider flags
     */
    public function testFilter(string $flag)
    {
        // when
        $result = preg::filter("~foo~$flag", 'bar', 'foo');
        // then
        $this->assertSame('bar', $result);
    }

    /**
     * @dataProvider flags
     */
    public function testFilterArray(string $flag)
    {
        // when
        $result = preg::filter(["~foo~$flag"], 'bar', 'foo');
        // then
        $this->assertSame('bar', $result);
    }

    /**
     * @dataProvider flags
     */
    public function testReplaceCallback(string $flag)
    {
        // when
        $result = preg::replace_callback("~foo~$flag", 'json_encode', 'foo');
        // then
        $this->assertSame('["foo"]', $result);
    }

    /**
     * @dataProvider flags
     */
    public function testReplaceCallbackArray(string $flag)
    {
        // when
        $result = preg::replace_callback_array(["~foo~$flag" => 'json_encode'], 'foo');
        // then
        $this->assertSame('["foo"]', $result);
    }

    /**
     * @dataProvider flags
     */
    public function testSplit(string $flag)
    {
        // when
        $parts = preg::split("~[,.]~$flag", 'a.b,c');
        // then
        $this->assertSame(['a', 'b', 'c'], $parts);
    }

    /**
     * @dataProvider flags
     */
    public function testGrep(string $flag)
    {
        // when
        $parts = preg::grep("~f.o~$flag", ['foo', 'bar', 'fao', 'bao']);
        // then
        $this->assertSame(['foo', 2 => 'fao'], $parts);
    }

    public function flags(): array
    {
        return [
            'i'    => ['i'],
            '\r'   => ["i\r"],
            '\n'   => ["i\n"],
            '\t'   => ["i\t"],
            '\f'   => ["i\f"],
            '\x0b' => ["i\x0b"],
        ];
    }
}
