<?php
namespace Test\Feature\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Runtime\ExplicitStringEncoding;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Details\MatchDetail
 */
class MatchDetailTest extends TestCase
{
    use ExplicitStringEncoding, AssertsGroup;

    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $detail = Pattern::of("Here's Johnny!")->match("Here's Johnny!")->first();
        // when
        $text = $detail->text();
        // then
        $this->assertSame("Here's Johnny!", $text);
    }

    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $detail = Pattern::of('Bond')->match('James Bond')->first();
        // when
        $text = "$detail";
        // then
        $this->assertSame('Bond', $text);
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        $detail = Pattern::of('foo bar €')->match('foo bar €')->first();
        // when
        $length = $detail->length();
        $byteLength = $detail->byteLength();
        // then
        $this->assertSame(9, $length);
        $this->assertSame(11, $byteLength);
    }

    /**
     * @test
     */
    public function shouldGet_subject()
    {
        // given
        $detail = Pattern::of('Previous', 'i')->match('My previous!')->first();
        // when
        $subject = $detail->subject();
        // then
        $this->assertSame('My previous!', $subject);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = $this->lastDetail(Pattern::of('\d+')->match('15, 46, 34, 18'));
        // when
        $index = $detail->index();
        // then
        $this->assertSame(3, $index);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = Pattern::of('Foo', 'i')->match('€Foo')->first();
        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();
        // then
        $this->assertSame(1, $offset);
        $this->assertSame(3, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGet_groups_offset()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupOffsets([3, 6], $groups);
        $this->assertGroupIndicesConsequetive($groups);
    }

    /**
     * @test
     */
    public function shouldGet_namedGroups_offsets()
    {
        // given
        $detail = Pattern::of('(?<value>12€)(?<unit>cm)', 'i')->match('€€ 12€cm')->first();
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupOffsets(['value' => 3, 'unit' => 6], $groups);
        $this->assertGroupIndices(['value' => 1, 'unit' => 2], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // when
        $groups = $detail->groups();
        // then
        $this->assertGroupTexts(['12€', 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_namedGroups()
    {
        // given
        $detail = Pattern::of('(?<value>12€)(?<unit>cm)', 'i')->match('€€ 12€cm')->first();
        // when
        $groups = $detail->namedGroups();
        // then
        $this->assertGroupTexts(['value' => '12€', 'unit' => 'cm'], $groups);
    }

    /**
     * @test
     */
    public function shouldGet_group_text()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // then
        $value = $detail->group(2)->text();
        // then
        $this->assertSame('cm', "$value");
    }

    /**
     * @test
     */
    public function shouldCast_group_toString()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // then
        $value = (string)$detail->group(2);
        // then
        $this->assertSame('cm', "$value");
    }

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // then
        $value = $detail->get(1);
        // then
        $this->assertSame('12€', "$value");
    }

    /**
     * @test
     */
    public function shouldGet_groupNames()
    {
        // given
        $detail = Pattern::of('(?<value>12€)(cm)(?<nothing>)', 'i')->match('€€ 12€cm')->first();
        // when, then
        $this->assertSame(['value', null, 'nothing'], $detail->groupNames());
        $this->assertGroupNames(['value', null, 'nothing'], $detail->groups());
        $this->assertGroupNames(['value' => 'value', 'nothing' => 'nothing'], $detail->namedGroups());
        $this->assertGroupIndices(['value' => 1, 'nothing' => 3], $detail->namedGroups());
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        $detail = Pattern::of('(?<foo>value)')->match('value')->first();
        // when
        $existent = $detail->groupExists('foo');
        $nonExistent = $detail->groupExists('bar');
        // then
        $this->assertTrue($existent);
        $this->assertFalse($nonExistent);
    }

    /**
     * @test
     */
    public function shouldGet_matched()
    {
        // given
        $detail = Pattern::of('(Foo)(Bar)?')->match('Foo')->first();
        // when
        $matched = $detail->matched(1);
        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_matched_ForUnmatchedGroup()
    {
        // given
        $detail = Pattern::of('(Foo)(Bar)?')->match('Foo')->first();
        // when
        $matched = $detail->matched(2);
        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldGet_matched_ForEmptyGroup()
    {
        // given
        $detail = Pattern::of('(Foo)()')->match('Foo')->first();
        // when
        $matched = $detail->matched(2);
        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldThrow_matched_OnNonExistentGroup()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'bar'");
        // when
        $detail->matched('bar');
    }

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $detail = Pattern::of('\w[\w ]+(?=,|$)')->match('Joffrey, Cersei, Ilyn Payne')->first();
        // when
        $all = $detail->all();
        // then
        $this->assertSame(['Joffrey', 'Cersei', 'Ilyn Payne'], $all);
    }

    /**
     * @test
     */
    public function shouldGet_group_all()
    {
        // given
        $detail = Pattern::of('(\d+)(?<unit>[cmk]?m)?')->match('12mm, 18km, 17, 19cm')->first();
        // when
        $all = $detail->group('unit')->all();
        // then
        $this->assertSame(['mm', 'km', null, 'cm'], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_group_all_OnNonexistentGroup()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        $detail->group('missing')->all();
    }

    /**
     * @test
     */
    public function shouldThrow_group_ForInvalidGroupNameType()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');
        // when
        $detail->group(true);
    }

    private function lastDetail(MatchPattern $match): MatchDetail
    {
        $all = $match->stream()->all();
        return \array_pop($all);
    }
}
