<?php
namespace Test\Feature\CleanRegex\Match\map;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use Test\Utils\TestCase\TestCasePasses;
use Test\Utils\TypeFunctions;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldMapIdentity()
    {
        // given
        $match = Pattern::of('\w[\w ]+')->match('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        $details = $match->map(Functions::identity());
        // then
        $this->assertStructure($details, [
            Expect::text('Joffrey'),
            Expect::text('Cersei'),
            Expect::text('Ilyn Payne'),
            Expect::text('The Hound'),
        ]);
    }

    /**
     * @test
     */
    public function shouldMapValues()
    {
        // given
        $match = Pattern::of('\w[\w ]+')->match('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        [$first, $second, $third, $fourth] = $match->map(Functions::eachNext(['One', true, false, null]));
        // then
        $this->assertSame('One', $first);
        $this->assertSame(true, $second);
        $this->assertSame(false, $third);
        $this->assertSame(null, $fourth);
    }

    /**
     * @test
     */
    public function shouldInvokeWithTypeDetail()
    {
        // given
        $match = Pattern::of('\w[\w ]+')->match('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        $match->map(TypeFunctions::assertTypeDetail());
        // then
        $this->pass();
    }

    /**
     * @test
     * @depends shouldInvokeWithTypeDetail
     */
    public function shouldInvokeWithDetail()
    {
        // given
        $subject = "Boil 'em, mash 'em, stick 'em in a stew";
        $match = Pattern::of('[^, ].+?(?=,|$)')->match($subject);
        // when
        $match->map(Functions::collect($details));
        // then
        $this->assertStructure($details, [
            Expect::text("Boil 'em"),
            Expect::text("mash 'em"),
            Expect::text("stick 'em in a stew"),
        ]);
        $this->assertDetailsIndexed(...$details);
        $this->assertDetailsSubject($subject, ...$details);
        $this->assertDetailsAll(["Boil 'em", "mash 'em", "stick 'em in a stew"], ...$details);
    }

    /**
     * @test
     */
    public function shouldNotInvoke_onUnmatchedSubject()
    {
        // given
        $match = Pattern::of('Gandalf the white?')->match('Gandalf the fool!');
        // when
        $match->map(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onUnmatchedSubject()
    {
        // given
        $match = Pattern::of('Equality')->match('Equity');
        // when
        $result = $match->map(Functions::fail());
        // then
        $this->assertEmpty($result);
    }
}
