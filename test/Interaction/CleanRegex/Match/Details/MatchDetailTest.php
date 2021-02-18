<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Adapter\RawMatchesToMatchAdapter;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $detail = $this->detailText('foo');

        // when
        $text = $detail->text();

        // then
        $this->assertSame('foo', $text);
    }

    /**
     * @test
     */
    public function shouldGet_toString()
    {
        // given
        $detail = $this->detailText('foo');

        // when
        $text = "$detail";

        // then
        $this->assertSame('foo', $text);
    }

    /**
     * @test
     */
    public function shouldGet_textLength()
    {
        // given
        $detail = $this->detailText('foo bar €');

        // when
        $length = $detail->textLength();
        $byteLength = $detail->textByteLength();

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
        $detail = $this->detail(['subject' => 'Foo bar']);

        // when
        $subject = $detail->subject();

        // then
        $this->assertSame('Foo bar', $subject);
    }

    /**
     * @test
     */
    public function shouldGet_index()
    {
        // given
        $detail = $this->detail(['index' => 3]);

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
        $detail = $this->detail(['subject' => '€Foo', 'matches' => [[['', 3]]]]);

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
        $detail = $this->detail(['subject' => '1€', 'matches' => [
            0     => [['a', 0]],
            'one' => [['a', 1]],
            1     => [['a', 1]],
            'two' => [['a', 4]],
            2     => [['a', 4]],
        ]]);

        // when
        $offsets = $detail->groups()->offsets();

        // then
        $this->assertSame([1, 2], $offsets);
    }

    /**
     * @test
     */
    public function shouldGet_namedGroups_offsets()
    {
        // given
        $_ = [['a', 10]];
        $one = [['a', 1]];
        $three = [['a', 5]];
        $detail = $this->detail(['subject' => '1€1', 'matches' => [
            0       => $_,
            'one'   => $one,
            1       => $one,
            2       => $_,
            'three' => $three,
            3       => $three,
        ]]);

        // when
        $offsets = $detail->namedGroups()->offsets();

        // then
        $expectedOffsets = [
            'one'   => 1,
            'three' => 3,
        ];
        $this->assertSame($expectedOffsets, $offsets);
    }

    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        $detail = $this->detailMatches([
            0     => [['a', 0]],
            'one' => [['b', 1]],
            1     => [['c', 1]],
            2     => [['d', 1]],
        ]);

        // when
        $groups = $detail->groups()->texts();

        // then
        $this->assertSame(['c', 'd'], $groups);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $detail = $this->detailMatches([
            0     => [['a', 0]],
            'one' => [['b', 1]],
            1     => [['c', 1]],
            2     => [['d', 1]],
        ]);

        // when
        $named = $detail->namedGroups()->texts();

        // then
        $this->assertSame(['one' => 'b'], $named);
    }

    /**
     * @test
     */
    public function shouldGet_group_text()
    {
        // given
        $detail = $this->detailMatches(['foo' => null, 3 => [['value', 0]]]);

        // then
        $value = $detail->group('foo')->text();

        // then
        $this->assertSame('value', "$value");
    }

    /**
     * @test
     */
    public function shouldGet_group_toString()
    {
        // given
        $detail = $this->detailMatches(['foo' => null, 3 => [['value', 0]]]);

        // then
        $value = (string)$detail->group('foo');

        // then
        $this->assertSame('value', "$value");
    }

    /**
     * @test
     */
    public function shouldGet_get()
    {
        // given
        $detail = $this->detailMatches(['foo' => null, 3 => [['value', 0]]]);

        // then
        $value = $detail->get('foo');

        // then
        $this->assertSame('value', "$value");
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $detail = $this->detailMatches(array_flip([0, 'one', 1, 2, 'three', 3]));

        // when
        $names = $detail->groupNames();

        // then
        $this->assertSame(['one', null, 'three'], $names);
    }

    /**
     * @test
     */
    public function shouldGet_hasGroup()
    {
        // given
        $detail = $this->detailMatches(['foo' => []]);

        // when
        $existent = $detail->hasGroup('foo');
        $nonExistent = $detail->hasGroup('bar');

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
        $detail = $this->detailMatches(['foo' => null, 1 => [['', 0]]]);

        // when
        $matched = $detail->matched('foo');

        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGet_matched_ForUnmatchedGroup()
    {
        // given
        $detail = $this->detailMatches(['bar' => null, 1 => [['', -1]]]);

        // when
        $surname = $detail->matched('bar');

        // then
        $this->assertFalse($surname);
    }

    /**
     * @test
     */
    public function shouldThrow_matched_OnNonExistentGroup()
    {
        // given
        $detail = $this->detailMatches([[]]);

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'foo'");

        // when
        $detail->matched('foo');
    }

    /**
     * @test
     */
    public function shouldGet_all()
    {
        // given
        $detail = $this->detailMatches([[['Joffrey', 0], ['Cersei', 1], ['Ilyn Payne', 2]]]);

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
        $detail = $this->detailMatches([
            'one' => null,
            1     => [['Tyler Durden', 1], ['c', -1], ['Marla Singer', 3]],
        ]);

        // when
        $all = $detail->group('one')->all();

        // then
        $this->assertSame(['Tyler Durden', null, 'Marla Singer'], $all);
    }

    /**
     * @test
     */
    public function shouldThrow_group_all_OnNonexistentGroup()
    {
        // given
        $detail = $this->detailMatches([[]]);

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
        $detail = $this->detail([]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, given: boolean (true)');

        // when
        $detail->group(true);
    }

    /**
     * @test
     */
    public function shouldPreserveUserData()
    {
        // given
        $detail = $this->detailMatches([[['', 1]]]);
        $mixed = (object)['value' => 'foo'];

        // when
        $detail->setUserData($mixed);
        $userData = $detail->getUserData();

        // then
        $this->assertSame($mixed, $userData);
    }

    private function detailText(string $text): Detail
    {
        return $this->detail(['matches' => [[[$text, 0]]]]);
    }

    private function detailMatches(array $matches): Detail
    {
        return $this->detail(['matches' => $matches]);
    }

    private function detail(array $parameters): Detail
    {
        $subject = $parameters['subject'] ?? '';
        $index = $parameters['index'] ?? 0;
        $matches = $parameters['matches'] ?? [];

        $rawMatches = new RawMatchesOffset($matches);
        return new MatchDetail(
            new Subject($subject),
            $index,
            -1,
            new RawMatchesToMatchAdapter($rawMatches, $index),
            new EagerMatchAllFactory($rawMatches),
            new UserData());
    }
}
