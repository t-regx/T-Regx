<?php
namespace Test\Interaction\TRegx\CleanRegex\Replace\Callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplaceCallbackObject;
use TRegx\SafeRegex\preg;

/**
 * @covers \TRegx\CleanRegex\Replace\Callback\ReplaceCallbackObject
 */
class ReplaceCallbackObjectTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateReplaceDetailObject()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '...hello there, general kenobi';

        $object = $this->create($pattern, $subject, 3, function (ReplaceDetail $detail) use ($subject) {
            // then
            $this->assertSame(['hello', 'there', 'general', 'kenobi'], $detail->all());
            $this->assertSame($subject, $detail->subject());
            $this->assertSame('hello', $detail->text());
            $this->assertSame(0, $detail->index());
            $this->assertSame(3, $detail->offset());
            $this->assertSame(3, $detail->modifiedOffset());
            return 'replacement';
        });

        // when
        $callback = $object->getCallback();
        $callback(['hello']);
    }

    /**
     * @test
     */
    public function shouldReturnCallbackResult()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '...hello there, general kenobi';

        $object = $this->create($pattern, $subject, 3, Functions::constant('replacement'));

        // when
        $callback = $object->getCallback();
        $result = $callback(['hello']);

        // then
        $this->assertSame('replacement', $result);
    }

    /**
     * @test
     */
    public function shouldModifyOffset()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '.cat .fish .horse .leopard .cat';

        $offsets = [];
        $modifiedOffsets = [];

        $object = $this->create($pattern, $subject, 5, function (ReplaceDetail $detail) use (&$offsets, &$modifiedOffsets) {
            $offsets[] = $detail->offset();
            $modifiedOffsets[] = $detail->modifiedOffset();
            return 'tiger';
        });

        // when
        $callback = $object->getCallback();
        $callback(['cat']);
        $callback(['fish']);
        $callback(['horse']);
        $callback(['leopard']);
        $callback(['cat']);

        // then
        $this->assertSame([1, 6, 12, 19, 28], $offsets);
        $this->assertSame([1, 8, 15, 22, 29], $modifiedOffsets);
    }

    /**
     * @test
     */
    public function shouldThrow_OnNonStringReplacement()
    {
        // given
        $object = $this->create('//', 'foo bar', 1, Functions::constant(2));

        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but integer (2) given');

        // when
        $callback = $object->getCallback();
        $callback(['foo']);
    }

    /**
     * @test
     */
    public function shouldNotThrow_OnMatchGroupReplacement()
    {
        // given
        $object = $this->create('/(?<g>\d)cm/', 'foo 2cm bar', 1, function (ReplaceDetail $detail) {
            return $detail->group('g');
        });

        // when
        $callback = $object->getCallback();
        $result = $callback(['2cm']);

        // then
        $this->assertSame('2', $result);
    }

    private function create(string $pattern, string $subject, int $limit, callable $callback): ReplaceCallbackObject
    {
        return new ReplaceCallbackObject($callback, new Subject($subject), $this->analyzePattern($pattern, $subject), $limit, new MatchStrategy());
    }

    private function analyzePattern($pattern, $subject): RawMatchesOffset
    {
        preg::match_all($pattern, $subject, $matches, \PREG_OFFSET_CAPTURE);
        return new RawMatchesOffset($matches);
    }
}
