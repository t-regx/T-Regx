<?php
namespace Test\Unit\TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Exception\CleanRegex\InvalidReplacementException;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
use TRegx\CleanRegex\Replace\Callback\ReplaceCallbackObject;
use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\preg;

class ReplaceCallbackObjectTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateReplaceMatchObject()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '...hello there, general kenobi';

        $object = $this->create($pattern, $subject, 3, function (ReplaceMatch $match) use ($subject) {
            // then
            $this->assertEquals(['hello', 'there', 'general'], $match->all());
            $this->assertEquals($subject, $match->subject());
            $this->assertEquals('hello', $match->text());
            $this->assertEquals(0, $match->index());
            $this->assertEquals(3, $match->offset());
            $this->assertEquals(3, $match->modifiedOffset());
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

        $object = $this->create($pattern, $subject, 3, function () {
            return 'replacement';
        });

        // when
        $callback = $object->getCallback();
        $result = $callback(['hello']);

        // then
        $this->assertEquals('replacement', $result);
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

        $object = $this->create($pattern, $subject, 5, function (ReplaceMatch $match) use (&$offsets, &$modifiedOffsets) {
            $offsets[] = $match->offset();
            $modifiedOffsets[] = $match->modifiedOffset();
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
        $this->assertEquals([1, 6, 12, 19, 28], $offsets);
        $this->assertEquals([1, 8, 15, 22, 29], $modifiedOffsets);
    }

    /**
     * @test
     */
    public function shouldThrow_OnNonStringReplacement()
    {
        // given
        $object = $this->create('//', 'foo bar', 1, function (ReplaceMatch $match) {
            return 2;
        });

        // then
        $this->expectException(InvalidReplacementException::class);

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
        $object = $this->create('/(?<g>\d)cm/', 'foo 2cm bar', 1, function (ReplaceMatch $match) {
            return $match->group('g');
        });

        // when
        $callback = $object->getCallback();
        $result = $callback(['2cm']);

        // then
        $this->assertEquals('2', $result);
    }

    private function create(string $pattern, string $subject, int $limit, callable $callback): ReplaceCallbackObject
    {
        return new ReplaceCallbackObject($callback, $subject, $this->analyzePattern($pattern, $subject), $limit);
    }

    private function analyzePattern($pattern, $subject): array
    {
        preg::match_all($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
        return $matches;
    }
}
