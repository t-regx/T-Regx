<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\ReplacePatternWithOptionalsImplTest\optional;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NonReplaced\NonMatchedMessage;
use TRegx\CleanRegex\Exception\CleanRegex\NotReplacedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePattern§;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;

class ReplacePatternWithOptionalsImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider methodsAndStrategies
     * @param string $method
     * @param NonReplacedStrategy $strategy
     */
    public function notReplaced_orThrow(string $method, array $arguments, NonReplacedStrategy $strategy)
    {
        // given
        $pattern = new InternalPattern('');

        $instance = $this->mock();
        $factory = $this->mockFactory($instance);
        $underTest = new ReplacePatternImpl($this->mock(), $pattern, 'subject', 0, $factory);

        // then
        $factory->expects($this->once())
            ->method('create')
            ->with($pattern, 'subject', 0, $strategy)
            ->willReturn('delegated');

        // when
        $result = $underTest->$method(...$arguments);

        // then
        $this->assertEquals($instance, $result);
    }

    function methodsAndStrategies(): array
    {
        $callback = function () {
        };
        return [
            ['orReturn', ['arg'], new ConstantResultStrategy('arg')],
            ['orThrow', [], new ThrowStrategy(NotReplacedException::class, new NonMatchedMessage())],
            ['orThrow', [InvalidArgumentException::class], new ThrowStrategy(InvalidArgumentException::class, new NonMatchedMessage())],
            ['orElse', [$callback], new ComputedSubjectStrategy($callback)],
        ];
    }

    /**
     * @param string|null $result
     * @return SpecificReplacePattern|MockObject
     */
    public function mock(string $result = null): SpecificReplacePattern
    {
        /** @var SpecificReplacePattern $delegate */
        $delegate = $this->createMock(SpecificReplacePattern::class);
        $delegate->method('with')->willReturn($result ?? '');
        return $delegate;
    }

    /**
     * @param SpecificReplacePattern $result
     * @return ReplacePattern§|MockObject
     */
    private function mockFactory(SpecificReplacePattern $result): ReplacePattern§
    {
        /** @var ReplacePattern§|MockObject $factory */
        $factory = $this->createMock(ReplacePattern§::class);
        $factory->method('create')->willReturn($result);
        return $factory;
    }
}
