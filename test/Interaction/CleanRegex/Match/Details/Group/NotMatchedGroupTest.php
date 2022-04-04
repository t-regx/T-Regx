<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExampleException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

/**
 * @covers \TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup
 */
class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     * @dataProvider optionalMethods
     * @param string $method
     * @param array $arguments
     * @param $expected
     */
    public function testMethodOptional(string $method, array $arguments, $expected)
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $matches = $matchGroup->$method(...$arguments);

        // then
        $this->assertSame($expected, $matches);
    }

    public function optionalMethods(): array
    {
        return [
            ['matched', [], false],
            ['equals', ['any'], false],
            ['name', [], 'first'],
            ['index', [], 1],
            ['orElse', [Functions::constant('result')], 'result'],
            ['orReturn', [13], 13],
        ];
    }

    /**
     * @test
     * @dataProvider nonOptionalMethods
     * @param string $method
     * @param array $arguments
     */
    public function testMethodNonOptional(string $method, array $arguments = [])
    {
        // given
        $matchGroup = $this->matchGroup();

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call $method() for group 'first', but the group was not matched");

        // when
        $matchGroup->$method(...$arguments);
    }

    public function nonOptionalMethods(): array
    {
        return [
            ['text', ['']],
            ['textLength', ['']],
            ['textByteLength', ['']],
            ['substitute', ['']],
            ['offset'],
            ['byteOffset'],
            ['tail'],
            ['byteTail'],
        ];
    }

    /**
     * @test
     */
    public function shouldControlMatched_orThrow()
    {
        // given
        $matchGroup = $this->matchGroup(new Subject('subject'));
        // then
        $this->expectException(ExampleException::class);
        // when
        $matchGroup->orThrow(new ExampleException());
    }

    private function matchGroup(Subject $subject = null): NotMatchedGroup
    {
        $string = $subject ?? 'Foo';
        return pattern($string . '(?<first>first)?')
            ->match($string)
            ->first(function (Detail $detail): NotMatchedGroup {
                /** @var NotMatchedGroup $group */
                $group = $detail->group('first');
                return $group;
            });
    }
}
