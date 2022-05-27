<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\usingDuplicateName;

use PHPUnit\Framework\TestCase;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;
use function pattern;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('One', $declared->text());
        $this->assertSame('Two', $parsed->text());
    }

    /**
     * @test
     */
    public function shouldGet_text_or()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('One', $declared->or('other'));
        $this->assertSame('Two', $parsed->or('other'));
    }

    /**
     * @test
     */
    public function shouldGetOr_forUnmatchedGroup()
    {
        // given
        Pattern::of('(?<group>Plane)?(?<group>Bird)?Superman', 'J')
            ->match('Superman')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertSame('other', $declared->or('other'));
        $this->assertSame('other', $parsed->or('other'));
    }

    /**
     * @test
     */
    public function shouldGet_get()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->get('group');
        $parsed = $detail->usingDuplicateName()->get('group');

        // then
        $this->assertSame('One', $declared);
        $this->assertSame('Two', $parsed);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame(0, $declared->offset());
        $this->assertSame(3, $parsed->offset());
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('group', $declared->name());
        $this->assertSame('group', $parsed->name());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');

        // then
        $this->assertSame(1, $declared->index());
    }

    public function detail(): Detail
    {
        return pattern('(?<group>One)(?<group>Two)', 'J')
            ->match('OneTwo')
            ->stream()
            ->first();
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     */
    public function shouldThrow_group_InvalidGroupName(string $method)
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '!@#' given");

        // when
        $detail->usingDuplicateName()->$method('!@#');
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     */
    public function shouldThrow_group_IntegerGroup(string $method)
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2' given");

        // when
        $detail->usingDuplicateName()->$method(2);
    }

    public function methods(): array
    {
        return [
            ['group'],
            ['get'],
            ['matched'],
        ];
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     */
    public function shouldThrowForMissingGroup(string $method)
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $detail->usingDuplicateName()->$method('missing');
    }

    /**
     * @test
     */
    public function shouldGroupBeMatched()
    {
        // given
        pattern('(?<group>One)(?<group>Two)?', 'J')->match('One')->first(DetailFunctions::out($detail));
        // when
        $matched = $detail->usingDuplicateName()->matched('group');
        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGroupNotBeMatched()
    {
        // given
        pattern('(?<group>One)?(?<group>Two)?', 'J')->match('Foo')->first(DetailFunctions::out($detail));
        // when
        $matched = $detail->usingDuplicateName()->matched('group');
        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        pattern('(?<group>\S+) (?<group>\S+)', 'J')
            ->match('Chrząszcz Wąż')
            ->first(DetailFunctions::out($detail));
        // when
        $group = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertSame(10, $group->offset());
        $this->assertSame(11, $group->byteOffset());
        $this->assertSame(3, $group->length());
        $this->assertSame(5, $group->byteLength());
        $this->assertSame(13, $group->tail());
        $this->assertSame(16, $group->byteTail());
    }

    /**
     * @test
     */
    public function shouldBeEqual()
    {
        // given
        pattern('(?<group>\S+) (?<group>\S+)', 'J')
            ->match('Chrząszcz Wąż')
            ->first(DetailFunctions::out($detail));
        // when
        $group = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertTrue($group->equals('Wąż'));
        $this->assertFalse($group->equals('Chrząszcz'));
    }

    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        pattern('(?<group>foo)?:(?<group>[\w ]+)', 'J')
            ->match(':near, :far, :wherever you are')
            ->first(DetailFunctions::out($detail));
        // when
        $group = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertSame(['near', 'far', 'wherever you are'], $group->all());
    }

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        pattern('(?<group>foo)?:(?<group>[\w ]+)', 'J')
            ->match(':near, :far, :wherever you are')
            ->first(DetailFunctions::out($detail));
        // when
        $group = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertSame(':near, :far, :wherever you are', $group->subject());
    }

    /**
     * @test
     */
    public function shouldGetUsedIdentifier()
    {
        // given
        pattern('(?<foo>Foo)', 'J')->match('Foo')->first(DetailFunctions::out($detail));
        // when
        $group = $detail->usingDuplicateName()->group('foo');
        // then
        $this->assertSame('foo', $group->usedIdentifier());
    }

    /**
     * @test
     */
    public function shouldGetAsInt()
    {
        // given
        Pattern::of('(?<group>123),(?<group>456)', 'J')
            ->match('123,456')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertSame(123, $declared->toInt());
        $this->assertSame(456, $parsed->toInt());
    }

    /**
     * @test
     */
    public function shouldGetAsIntBase16()
    {
        // given
        Pattern::of('(?<group>123a),(?<group>456a)', 'J')
            ->match('123a,456a')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertSame(4666, $declared->toInt(16));
        $this->assertSame(17770, $parsed->toInt(16));
    }

    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        Pattern::of('(?<group>___),(?<group>123)', 'J')
            ->match('___,123')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertFalse($declared->isInt());
        $this->assertTrue($parsed->isInt());
    }

    /**
     * @test
     */
    public function shouldNotBeInt()
    {
        // given
        Pattern::of('(?<group>123),(?<group>___)', 'J')
            ->match('123,___')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');
        // then
        $this->assertTrue($declared->isInt());
        $this->assertFalse($parsed->isInt());
    }

    /**
     * @test
     * @deprecated
     */
    public function shouldSubstitute()
    {
        // given
        Pattern::of('<(?<group>Old):(?<group>Old)>', 'J')
            ->match('Subject <Old:Old>.')
            ->first(DetailFunctions::out($detail));
        // when
        $declared = $detail->group('group')->substitute('New');
        $parsed = $detail->usingDuplicateName()->group('group')->substitute('New');
        // then
        $this->assertEquals('<New:Old>', $declared);
        $this->assertEquals('<Old:New>', $parsed);
    }
}
