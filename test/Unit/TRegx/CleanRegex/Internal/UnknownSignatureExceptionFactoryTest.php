<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use Error;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Test\Utils\ClassWithDefaultConstructor;
use Test\Utils\ClassWithoutSuitableConstructor;
use Test\Utils\ClassWithStringParamConstructor;
use Test\Utils\ClassWithTwoStringParamsConstructor;
use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\ClassExpectedException;
use TRegx\CleanRegex\Exception\CleanRegex\NoSuitableConstructorException;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\UnknownSignatureExceptionFactory;
use Utils\ClassWithErrorInConstructor;

class UnknownSignatureExceptionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNotExistingClass()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory('Namespace\NoSuchClass');

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'Namespace\NoSuchClass' does not exists");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldThrow_onInterface()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(Throwable::class);

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("'Throwable' is not a class, but an interface");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldThrow_onClassThatIsNotThrowable()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(stdClass::class);

        // then
        $this->expectException(ClassExpectedException::Class);
        $this->expectExceptionMessage("Class 'stdClass' is not throwable");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldThrow_onClassWithoutSuitableConstructor()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithoutSuitableConstructor::class);

        // then
        $this->expectException(NoSuitableConstructorException::Class);
        $this->expectExceptionMessage("Class 'Test\Utils\ClassWithoutSuitableConstructor' doesn't have a constructor with supported signature");

        // when
        $factory->create('');
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageAndSubjectParams()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithTwoStringParamsConstructor::class);

        // when
        /** @var ClassWithTwoStringParamsConstructor $exception */
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithTwoStringParamsConstructor::class, $exception);
        $this->assertEquals(SubjectNotMatchedException::MESSAGE, $exception->getMessage());
        $this->assertEquals('my subject', $exception->getSubject());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withMessageParam()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithStringParamConstructor::class);

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf(ClassWithStringParamConstructor::class, $exception);
        $this->assertEquals(SubjectNotMatchedException::MESSAGE, $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldInstantiate_withDefaultConstructor()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithDefaultConstructor::class);

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
        $factory = new UnknownSignatureExceptionFactory($className);

        // when
        $exception = $factory->create('my subject');

        // then
        $this->assertInstanceOf($className, $exception);
        $this->assertEquals(SubjectNotMatchedException::MESSAGE, $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldRethrow_errorWhileInstantiating()
    {
        // given
        $factory = new UnknownSignatureExceptionFactory(ClassWithErrorInConstructor::class);

        // then
        $this->expectException(Error::class);

        // when
        $factory->create('my subject');
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
