<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use TRegx\CleanRegex\Internal\Exception\Messages\NonReplacedMessage;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;
use TRegx\CleanRegex\Internal\StringSubject;

/**
 * @covers \TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy
 */
class ThrowStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow()
    {
        // given
        $strategy = new ThrowStrategy(CustomSubjectException::class, new NonReplacedMessage());

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Replacements were supposed to be performed, but subject doesn't match the pattern");

        // when
        try {
            $strategy->substitute(new StringSubject('foo'));
        } catch (CustomSubjectException $exception) {
            $this->assertSame('foo', $exception->subject);
            throw $exception;
        }
    }
}
