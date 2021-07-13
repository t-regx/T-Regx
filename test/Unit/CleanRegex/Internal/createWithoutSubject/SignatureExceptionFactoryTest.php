<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\createWithoutSubject;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithoutSuitableConstructor;
use TRegx\CleanRegex\Exception\NoSuitableConstructorException;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

/**
 * @covers \TRegx\CleanRegex\Internal\SignatureExceptionFactory
 */
class SignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onClassWithoutSuitableConstructor()
    {
        // given
        $factory = new SignatureExceptionFactory(new FirstMatchMessage());

        // then
        $this->expectException(NoSuitableConstructorException::Class);
        $this->expectExceptionMessage("Class 'Test\Utils\ClassWithoutSuitableConstructor' doesn't have a constructor with supported signature");

        // when
        $factory->createWithoutSubject(ClassWithoutSuitableConstructor::class);
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $factory = new SignatureExceptionFactory(new FirstMatchMessage());

        // when
        $exception = $factory->createWithoutSubject(ClassWithDefaultConstructor::class);

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
        $exception = $factory->createWithoutSubject($className);

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertSame('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    public function exceptions(): array
    {
        return [
            [Exception::class],
            [InvalidArgumentException::class],
        ];
    }
}
