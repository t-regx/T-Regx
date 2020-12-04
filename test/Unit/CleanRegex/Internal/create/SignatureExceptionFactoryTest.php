<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\create;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithStringParamConstructor;
use Test\Utils\ClassWithTwoStringParamsConstructor;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

class SignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInstantiate_withMessageAndSubjectParams()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithTwoStringParamsConstructor::class, new FirstMatchMessage());

        // when
        /** @var ClassWithTwoStringParamsConstructor $exception */
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithTwoStringParamsConstructor::class, $exception);
        $this->assertEquals('Expected to get the first match, but subject was not matched', $exception->getMessage());
        $this->assertEquals('my subject', $exception->getSubject());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageParam()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithStringParamConstructor::class, new FirstMatchMessage());

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithStringParamConstructor::class, $exception);
        $this->assertEquals('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $factory = new SignatureExceptionFactory(ClassWithDefaultConstructor::class, new FirstMatchMessage());

        // when
        $exception = $factory->create('my subject');

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
        $factory = new SignatureExceptionFactory($className, new FirstMatchMessage());

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertEquals('Expected to get the first match, but subject was not matched', $exception->getMessage());
    }

    public function exceptions()
    {
        return [
            [Exception::class],
            [InvalidArgumentException::class],
            [SubjectNotMatchedException::class],
        ];
    }
}
