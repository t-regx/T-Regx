<?php
namespace Test\Feature\CleanRegex\Match\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use TestCasePasses, AssertsDetail;

    /**
     * @test
     */
    public function shouldMapIdentity()
    {
        // given
        $search = Pattern::of('\w[\w ]+')->search('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        $killList = $search->map(Functions::identity());
        // then
        $this->assertSame(['Joffrey', 'Cersei', 'Ilyn Payne', 'The Hound'], $killList);
    }

    /**
     * @test
     */
    public function shouldMapValues()
    {
        // given
        $search = Pattern::of('\w[\w ]+')->search('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        [$first, $second, $third, $fourth] = $search->map(Functions::eachNext(['One', true, false, null]));
        // then
        $this->assertSame('One', $first);
        $this->assertSame(true, $second);
        $this->assertSame(false, $third);
        $this->assertSame(null, $fourth);
    }

    /**
     * @test
     */
    public function shouldInvokeWithTypeString()
    {
        // given
        $search = Pattern::of('\w[\w ]+')->search('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        $search->map(TypeFunctions::assertTypeString());
        // then
        $this->pass();
    }

    /**
     * @test
     * @depends shouldInvokeWithTypeString
     */
    public function shouldInvokeWithText()
    {
        // given
        $search = Pattern::of('[^, ].+?(?=,|$)')->search("Boil 'em, mash 'em, stick 'em in a stew");
        // when
        $search->map(Functions::collect($details));
        // then
        $this->assertSame(["Boil 'em", "mash 'em", "stick 'em in a stew"], $details);
    }

    /**
     * @test
     */
    public function shouldNotInvoke_onUnmatchedSubject()
    {
        // given
        $search = Pattern::of('Gandalf the white?')->search('Gandalf the fool!');
        // when
        $search->map(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onUnmatchedSubject()
    {
        // given
        $search = Pattern::of('Equality')->search('Equity');
        // when
        $result = $search->map(Functions::fail());
        // then
        $this->assertEmpty($result);
    }
}
