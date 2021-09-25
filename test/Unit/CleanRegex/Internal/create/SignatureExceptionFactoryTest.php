<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\create;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithStringParamConstructor;
use Test\Utils\ClassWithTwoStringParamsConstructor;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Message\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\StringSubject;

/**
 * @covers \TRegx\CleanRegex\Internal\SignatureExceptionFactory
 */
class SignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInstantiate_withMessageAndSubjectParams()
    {
        // given
        $factory = new SignatureExceptionFactory(new FirstMatchMessage());

        // when
        /** @var ClassWithTwoStringParamsConstructor $exception */
        $exception = $factory->create(ClassWithTwoStringParamsConstructor::class, new StringSubject('my subject'));

        // then
        $this->assertInstanceOf(ClassWithTwoStringParamsConstructor::class, $exception);
        $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
        $this->assertSame('my subject', $exception->getSubject());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageParam()
    {
        // given
        $factory = new SignatureExceptionFactory(new FirstMatchMessage());

        // when
        $exception = $factory->create(ClassWithStringParamConstructor::class, new StringSubject('my subject'));

        // then
        $this->assertInstanceOf(ClassWithStringParamConstructor::class, $exception);
        $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $factory = new SignatureExceptionFactory(new FirstMatchMessage());

        // when
        $exception = $factory->create(ClassWithDefaultConstructor::class, new StringSubject('my subject'));

        // then
        $this->assertInstanceOf(ClassWithDefaultConstructor::class, $exception);
    }

    /**
     * @test
     * @dataProvider exceptions
     * @param string $className
     */
    public function shouldInstantiate_withMessage(string $className)
    {
        // given
        $factory = new SignatureExceptionFactory(new FirstMatchMessage());

        // when
        $exception = $factory->create($className, new StringSubject('my subject'));

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    public function exceptions(): array
    {
        return [
            [Exception::class],
            [InvalidArgumentException::class],
            [SubjectNotMatchedException::class],
        ];
    }
}
