<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\ReplacePatternWithOptionalsImplTest\optional;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NotReplacedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NonReplacedMessage;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedSubjectStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantResultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\CustomThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePatternFactory;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;

class ReplacePatternImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider methodsAndStrategies
     * @param string $method
     * @param array $arguments
     * @param ReplaceSubstitute $substitute
     */
    public function notReplaced_orThrow(string $method, array $arguments, ReplaceSubstitute $substitute)
    {
        // given
        $instance = $this->mock();
        $underTest = new ReplacePatternImpl(
            $this->mock(),
            InternalPattern::pcre('//'),
            'subject',
            0,
            $this->mockFactory(InternalPattern::pcre('//'), $substitute, $instance));

        // when
        $result = $underTest->$method(...$arguments);

        // then
        $this->assertSame($instance, $result);
    }

    function methodsAndStrategies(): array
    {
        return [
            ['otherwiseReturning', ['arg'], new ConstantResultStrategy('arg')],
            ['otherwiseThrowing', [], new CustomThrowStrategy(NotReplacedException::class, new NonReplacedMessage())],
            ['otherwiseThrowing', [InvalidArgumentException::class], new CustomThrowStrategy(InvalidArgumentException::class, new NonReplacedMessage())],
            ['otherwise', [Functions::any()], new ComputedSubjectStrategy(Functions::any())],
        ];
    }

    public function mock(string $result = null): SpecificReplacePattern
    {
        /** @var SpecificReplacePattern|MockObject $delegate */
        $delegate = $this->createMock(SpecificReplacePattern::class);
        $delegate->method('with')->willReturn($result ?? '');
        return $delegate;
    }

    private function mockFactory(InternalPattern $pattern, ReplaceSubstitute $substitute, $instance): ReplacePatternFactory
    {
        /** @var ReplacePatternFactory|MockObject $factory */
        $factory = $this->createMock(ReplacePatternFactory::class);
        $factory->expects($this->once())->method('create')->with($pattern, 'subject', 0, $substitute)->willReturn($instance);
        return $factory;
    }
}
