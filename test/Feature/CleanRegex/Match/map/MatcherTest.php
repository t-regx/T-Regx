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
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use TestCasePasses, AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldMapIdentity()
    {
        // given
        $matcher = Pattern::of('\w[\w ]+')->match('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        $details = $matcher->map(Functions::identity());
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
        $matcher = Pattern::of('\w[\w ]+')->match('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        [$first, $second, $third, $fourth] = $matcher->map(Functions::eachNext(['One', true, false, null]));
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
        $matcher = Pattern::of('\w[\w ]+')->match('Joffrey, Cersei, Ilyn Payne, The Hound');
        // when
        $matcher->map(TypeFunctions::assertTypeDetail());
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
        $pattern = Pattern::of('[^, ].+?(?=,|$)');
        $subject = "Boil 'em, mash 'em, stick 'em in a stew";
        $matcher = $pattern->match($subject);
        // when
        $matcher->map(Functions::collect($details));
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
        $matcher = Pattern::of('Gandalf the white?')->match('Gandalf the fool!');
        // when
        $matcher->map(Functions::fail());
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onUnmatchedSubject()
    {
        // given
        $matcher = Pattern::of('Equality')->match('Equity');
        // when
        $result = $matcher->map(Functions::fail());
        // then
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $pattern = Pattern::of('(?<name>indexed):(?<name>runtime)', 'J');
        $matcher = $pattern->match('indexed:runtime');
        // when
        [$detail] = $matcher->map(Functions::identity());
        // then
        $this->assertSame('indexed', $detail->get('name'));
    }

    /**
     * @test
     */
    public function shouldGetGroupMatched()
    {
        // given
        $pattern = Pattern::of('Foo (?<name>bad){0}(?<name>good)', 'J');
        $matcher = $pattern->match('Foo good');
        // when
        [$detail] = $matcher->map(Functions::identity());
        // then
        $this->assertFalse($detail->group('name')->matched());
    }
}
